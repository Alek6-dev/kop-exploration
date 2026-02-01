<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\CreateStrategyDriverPerformance;

use App\Shared\Application\Command\AsCommandHandler;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\StrategyDriverPerformance;

#[AsCommandHandler]
final readonly class CreateStrategyDriverPerformanceCommandHandler
{
    public function __invoke(CreateStrategyDriverPerformanceCommand $command): StrategyDriverPerformanceInterface
    {
        return (new StrategyDriverPerformance())
             ->setStrategy($command->strategy)
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
