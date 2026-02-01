<?php

declare(strict_types=1);

namespace App\Performance\Application\Command\SavePerformance;

use App\Performance\Domain\Enum\QualificationPositionPointEnum;
use App\Performance\Domain\Enum\RacePositionPointEnum;
use App\Performance\Domain\Enum\SprintPositionPointEnum;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Domain\Repository\DriverPerformanceRepositoryInterface;
use App\Performance\Infrastructure\Doctrine\Entity\DriverPerformance;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class SaveDriverPerformanceCommandHandler
{
    public function __construct(private DriverPerformanceRepositoryInterface $repository)
    {
    }

    public function __invoke(SaveDriverPerformanceCommand $command): DriverPerformanceInterface
    {
        /** @var ?DriverPerformanceInterface $model */
        $model = $this->repository
            ->withRace($command->race)
            ->withSeason($command->season)
            ->withDriver($command->driver)
            ->first()
        ;

        if (!$model) {
            $model = (new DriverPerformance())
                ->setRace($command->race)
                ->setSeason($command->season)
                ->setDriver($command->driver)
            ;
        }

        $positionGain = $command->positionGain;
        $sprintPosition = $command->sprintPosition;
        $sprintPoints = $sprintPosition ? SprintPositionPointEnum::getPointsFromPosition($sprintPosition) : null;
        $qualificationPosition = $command->qualificationPosition;
        $qualificationPoints = QualificationPositionPointEnum::getPointsFromPosition($qualificationPosition);
        $racePosition = $command->position;
        $racePoints = RacePositionPointEnum::getPointsFromPosition($racePosition);
        $model
            ->setPositionGain($positionGain)
            ->setPosition($racePosition)
            ->setRacePoints($racePoints)
            ->setSprintPosition($sprintPosition)
            ->setSprintPoints($sprintPoints)
            ->setQualificationPosition($qualificationPosition)
            ->setQualificationPoints($qualificationPoints)
            ->setScore($positionGain + $sprintPoints?->value + $qualificationPoints->value + $racePoints->value)
            ->setScoreWithBonus($positionGain + $sprintPoints?->value + $qualificationPoints->value + $racePoints->value)
            ->setResult($command->result)
        ;

        return $model;
    }
}
