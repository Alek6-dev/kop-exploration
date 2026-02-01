<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use App\Strategy\Domain\Model\StrategyTeamPerformanceInterface;
use App\Team\Infrastructure\ApiPlatform\Resource\TeamResource;

class TeamPerformanceResource
{
    public function __construct(
        #[ApiProperty(readableLink: true, writableLink: false)]
        public TeamResource $teamResource,
        public int $teamMultiplier,
        public ?int $position,
        public ?int $points,
        public ?int $score,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?TeamPerformanceReferenceResource $reference,
    ) {
    }

    public static function fromModel(StrategyTeamPerformanceInterface $model): self
    {
        return new self(
            TeamResource::fromModel($model->getTeam()),
            $model->getMultiplier(),
            $model->getPosition(),
            $model->getPoints(),
            $model->getScore(),
            TeamPerformanceReferenceResource::fromModel($model->getPerformanceReference()),
        );
    }
}
