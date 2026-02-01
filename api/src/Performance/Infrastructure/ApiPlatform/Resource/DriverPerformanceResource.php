<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use App\Driver\Infrastructure\ApiPlatform\Resource\DriverResource;
use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Performance\Domain\Enum\QualificationPositionPointEnum;
use App\Performance\Domain\Enum\RacePositionPointEnum;
use App\Performance\Domain\Enum\SprintPositionPointEnum;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;

class DriverPerformanceResource
{
    public function __construct(
        #[ApiProperty(readableLink: true, writableLink: false)]
        public DriverResource $driverResource,
        public QualificationPositionPointEnum $qualificationPositionPoint,
        public RacePositionPointEnum $racePositionPoint,
        public ?SprintPositionPointEnum $sprintPositionPoint,
        public ?int $positionGain,
        public ?int $position,
        public ?int $points,
        public ?int $score,
        public ?int $scoreWithBonus,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverPerformanceReferenceResource $reference,
    ) {
    }

    public static function fromModel(StrategyDriverPerformanceInterface|DuelDriverPerformanceInterface $model): self
    {
        return new self(
            DriverResource::fromModel($model->getDriver()),
            $model->getQualificationPoints(),
            $model->getRacePoints(),
            $model->getSprintPoints(),
            $model->getPositionGain(),
            $model->getPosition(),
            $model->getPoints(),
            $model->getScore(),
            $model->getScoreWithBonus(),
            DriverPerformanceReferenceResource::fromModel($model->getPerformanceReference()),
        );
    }
}
