<?php

declare(strict_types=1);

namespace App\Duel\Application\Command\CreateDuelDriverPerformance;

use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Duel\Infrastructure\Doctrine\Entity\DuelDriverPerformance;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class CreateDuelDriverPerformanceCommandHandler
{
    public function __invoke(CreateDuelDriverPerformanceCommand $command): DuelDriverPerformanceInterface
    {
        return (new DuelDriverPerformance())
            ->setDuel($command->duel)
            ->setPerformanceReference($command->driverPerformance)
            ->setDriver($command->driverPerformance->getDriver())
            ->setPosition($command->driverPerformance->getPosition())
            ->setQualificationPosition($command->driverPerformance->getQualificationPosition())
            ->setSprintPosition($command->driverPerformance->getSprintPosition())
            ->setPositionGain($command->positionGain)
            ->setSprintPoints($command->sprintPoints)
            ->setQualificationPoints($command->qualificationPoints)
            ->setRacePoints($command->racePoints)
            ->setScore($command->positionGain + $command->sprintPoints?->value + $command->qualificationPoints?->value + $command->racePoints?->value)
            ->setScoreWithBonus($command->positionGain + $command->sprintPoints?->value + $command->qualificationPoints?->value + $command->racePoints?->value)
        ;
    }
}
