<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\ApiPlatform\Resource;

use App\Performance\Domain\Enum\QualificationPositionPointEnum;
use App\Performance\Domain\Enum\RacePositionPointEnum;
use App\Performance\Domain\Enum\SprintPositionPointEnum;
use App\Performance\Domain\Model\DriverPerformanceInterface;

class DriverPerformanceReferenceResource
{
    public function __construct(
        public QualificationPositionPointEnum $qualificationPositionPoint,
        public RacePositionPointEnum $racePositionPoint,
        public ?SprintPositionPointEnum $sprintPositionPoint,
        public ?int $positionGain,
        public ?int $position,
        public ?int $points,
        public ?int $score,
    ) {
    }

    public static function fromModel(DriverPerformanceInterface $model): self
    {
        return new self(
            $model->getQualificationPoints(),
            $model->getRacePoints(),
            $model->getSprintPoints(),
            $model->getPositionGain(),
            $model->getPosition(),
            $model->getPoints(),
            $model->getScore(),
        );
    }
}
