<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\CreateSeasonRoster;

use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\SeasonGame\Domain\Exception\SeasonGameException;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRoster;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRosterDriver;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRosterTeam;
use App\Shared\Application\Command\AsCommandHandler;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommandHandler]
final readonly class CreateSeasonRosterCommandHandler
{
    private const int INITIAL_BUDGET = 500;
    private const float USAGE_RATIO = 0.6;

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(CreateSeasonRosterCommand $command): SeasonRoster
    {
        $participation = $command->participation;

        if ($participation->hasRoster()) {
            throw SeasonGameException::rosterAlreadyExists();
        }

        $season = $participation->getSeason();

        $remainingRaces = $this->countRemainingRaces($season);

        $drivers = $this->loadDrivers($command->driverUuids);
        $teams = $this->loadTeams($command->teamUuids);

        $budgetSpent = $this->calculateBudget($drivers, $teams);

        if ($budgetSpent > self::INITIAL_BUDGET) {
            throw SeasonGameException::budgetExceeded();
        }

        $maxUsages = max(1, (int) ceil($remainingRaces * self::USAGE_RATIO));

        $roster = new SeasonRoster();
        $roster->setParticipation($participation);
        $roster->setBudgetSpent($budgetSpent);

        foreach ($drivers as $driver) {
            $rosterDriver = new SeasonRosterDriver();
            $rosterDriver->setDriver($driver);
            $rosterDriver->setPurchasePrice($driver->getMinValue() ?? 0);
            $rosterDriver->setMaxUsages($maxUsages);
            $rosterDriver->setUsagesLeft($maxUsages);
            $roster->addDriver($rosterDriver);
        }

        foreach ($teams as $team) {
            $rosterTeam = new SeasonRosterTeam();
            $rosterTeam->setTeam($team);
            $rosterTeam->setPurchasePrice($team->getMinValue() ?? 0);
            $rosterTeam->setMaxUsages($maxUsages);
            $rosterTeam->setUsagesLeft($maxUsages);
            $roster->addTeam($rosterTeam);
        }

        $participation->setRoster($roster);
        $participation->debitWallet($budgetSpent);

        return $roster;
    }

    private function countRemainingRaces(object $season): int
    {
        $now = new \DateTimeImmutable();

        $count = $this->em->createQuery(
            'SELECT COUNT(sr) FROM App\Season\Infrastructure\Doctrine\Entity\SeasonRace sr
             WHERE sr.season = :season AND sr.date > :now'
        )
            ->setParameter('season', $season)
            ->setParameter('now', $now)
            ->getSingleScalarResult();

        return max(1, (int) $count);
    }

    /** @return Driver[] */
    private function loadDrivers(array $uuids): array
    {
        $drivers = [];
        foreach ($uuids as $uuid) {
            $driver = $this->em->getRepository(Driver::class)->findOneBy(['uuid' => $uuid]);
            if (!$driver) {
                throw new \InvalidArgumentException(sprintf('Driver "%s" not found.', $uuid));
            }
            $drivers[] = $driver;
        }

        return $drivers;
    }

    /** @return Team[] */
    private function loadTeams(array $uuids): array
    {
        $teams = [];
        foreach ($uuids as $uuid) {
            $team = $this->em->getRepository(Team::class)->findOneBy(['uuid' => $uuid]);
            if (!$team) {
                throw new \InvalidArgumentException(sprintf('Team "%s" not found.', $uuid));
            }
            $teams[] = $team;
        }

        return $teams;
    }

    private function calculateBudget(array $drivers, array $teams): int
    {
        $total = 0;
        foreach ($drivers as $driver) {
            $total += $driver->getMinValue() ?? 0;
        }
        foreach ($teams as $team) {
            $total += $team->getMinValue() ?? 0;
        }

        return $total;
    }
}
