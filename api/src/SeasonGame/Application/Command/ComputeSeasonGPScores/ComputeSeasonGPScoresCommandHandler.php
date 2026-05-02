<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\ComputeSeasonGPScores;

use App\Performance\Infrastructure\Doctrine\Entity\DriverPerformance;
use App\Performance\Infrastructure\Doctrine\Entity\TeamPerformance;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonGPStrategyRepository;
use App\Shared\Application\Command\AsCommandHandler;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommandHandler]
final readonly class ComputeSeasonGPScoresCommandHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private DoctrineSeasonGPStrategyRepository $strategyRepository,
    ) {
    }

    public function __invoke(ComputeSeasonGPScoresCommand $command): void
    {
        /** @var ?Race $race */
        $race = $this->em->getRepository(Race::class)->findOneBy(['uuid' => $command->raceUuid]);
        if (!$race) {
            return;
        }

        $strategies = $this->strategyRepository->findUncomputedStrategiesForRace($command->raceUuid);

        foreach ($strategies as $strategy) {
            $driver1 = $strategy->getDriver1()->getDriver();
            $driver2 = $strategy->getDriver2()->getDriver();
            $team = $strategy->getTeam()->getTeam();

            $driver1Perf = $this->em->getRepository(DriverPerformance::class)->findOneBy([
                'race' => $race,
                'driver' => $driver1,
            ]);

            $driver2Perf = $this->em->getRepository(DriverPerformance::class)->findOneBy([
                'race' => $race,
                'driver' => $driver2,
            ]);

            $teamPerf = $this->em->getRepository(TeamPerformance::class)->findOneBy([
                'race' => $race,
                'team' => $team,
            ]);

            $score1 = $driver1Perf?->getScore() ?? 0;
            $score2 = $driver2Perf?->getScore() ?? 0;
            $multiplier = $teamPerf ? ($teamPerf->getMultiplierWithBonus() ?? $teamPerf->getMultiplier()) : 10;

            $totalPoints = (int) round((($score1 * 2) + $score2) * ($multiplier / 10));

            $strategy->setPoints($totalPoints);
            $strategy->lock();
            $strategy->getDriver1()->decrementUsage();
            $strategy->getDriver2()->decrementUsage();
            $strategy->getTeam()->decrementUsage();

            $strategy->getParticipation()->addPoints($totalPoints);
        }

        $this->distributeRewards($command->raceUuid);

        $this->em->flush();
    }

    private function distributeRewards(string $raceUuid): void
    {
        $strategies = $this->strategyRepository->findGPRanking($raceUuid);
        $total = count($strategies);
        if (0 === $total) {
            return;
        }

        foreach ($strategies as $index => $strategy) {
            $percentile = (($index + 1) / $total) * 100;
            $reward = $this->getRewardForPercentile($percentile);
            if ($reward > 0) {
                $strategy->getParticipation()->creditWallet($reward);
            }
        }
    }

    private function getRewardForPercentile(float $percentile): int
    {
        return match (true) {
            $percentile <= 1 => 50,
            $percentile <= 5 => 30,
            $percentile <= 10 => 20,
            $percentile <= 25 => 15,
            $percentile <= 50 => 10,
            $percentile <= 75 => 5,
            default => 2,
        };
    }
}
