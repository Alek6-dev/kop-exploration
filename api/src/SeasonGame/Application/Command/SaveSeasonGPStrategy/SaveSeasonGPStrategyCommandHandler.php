<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\SaveSeasonGPStrategy;

use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\SeasonGame\Domain\Exception\SeasonGameException;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonGPStrategy;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRosterDriver;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRosterTeam;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonGPStrategyRepository;
use App\Shared\Application\Command\AsCommandHandler;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommandHandler]
final readonly class SaveSeasonGPStrategyCommandHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private DoctrineSeasonGPStrategyRepository $strategyRepository,
    ) {
    }

    public function __invoke(SaveSeasonGPStrategyCommand $command): SeasonGPStrategy
    {
        $participation = $command->participation;
        $roster = $participation->getRoster();

        if (!$roster) {
            throw SeasonGameException::rosterNotFound();
        }

        /** @var ?Race $race */
        $race = $this->em->getRepository(Race::class)->findOneBy(['uuid' => $command->raceUuid]);
        if (!$race) {
            throw new \InvalidArgumentException('Race not found.');
        }

        $this->assertDeadlineNotPassed($race);

        $existing = $this->strategyRepository->findByParticipationAndRace($participation, $command->raceUuid);

        if ($existing && $existing->isLocked()) {
            $hasParcFerme = $existing->hasBonusOfType('parc_ferme');
            if (!$hasParcFerme) {
                throw SeasonGameException::strategyAlreadyLocked();
            }
        }

        $driver1 = $this->findRosterDriver($roster->getDrivers()->toArray(), $command->driver1Uuid);
        $driver2 = $this->findRosterDriver($roster->getDrivers()->toArray(), $command->driver2Uuid);
        $team = $this->findRosterTeam($roster->getTeams()->toArray(), $command->teamUuid);

        if (!$driver1->hasUsagesLeft()) {
            throw SeasonGameException::noUsagesLeft($command->driver1Uuid);
        }
        if (!$driver2->hasUsagesLeft()) {
            throw SeasonGameException::noUsagesLeft($command->driver2Uuid);
        }
        if (!$team->hasUsagesLeft()) {
            throw SeasonGameException::noUsagesLeft($command->teamUuid);
        }

        if (!$existing) {
            $strategy = new SeasonGPStrategy();
            $strategy->setParticipation($participation);
            $strategy->setRace($race);
        } else {
            $strategy = $existing;
        }

        $strategy->setDriver1($driver1);
        $strategy->setDriver2($driver2);
        $strategy->setTeam($team);

        return $strategy;
    }

    private function assertDeadlineNotPassed(Race $race): void
    {
        $seasonRace = $this->em->getRepository(SeasonRace::class)->findOneBy(['race' => $race]);
        if (!$seasonRace) {
            return;
        }

        $deadline = $seasonRace->getLimitStrategyDate();
        if ($deadline && new \DateTimeImmutable() > $deadline) {
            throw SeasonGameException::strategyDeadlinePassed();
        }
    }

    private function findRosterDriver(array $rosterDrivers, string $uuid): SeasonRosterDriver
    {
        foreach ($rosterDrivers as $rd) {
            if ($rd->getDriver()->getUuid() === $uuid) {
                return $rd;
            }
        }
        throw new \InvalidArgumentException(sprintf('Driver "%s" not in roster.', $uuid));
    }

    private function findRosterTeam(array $rosterTeams, string $uuid): SeasonRosterTeam
    {
        foreach ($rosterTeams as $rt) {
            if ($rt->getTeam()->getUuid() === $uuid) {
                return $rt;
            }
        }
        throw new \InvalidArgumentException(sprintf('Team "%s" not in roster.', $uuid));
    }
}
