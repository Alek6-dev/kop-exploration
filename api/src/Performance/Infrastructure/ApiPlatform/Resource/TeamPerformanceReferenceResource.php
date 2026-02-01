<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\ApiPlatform\Resource;

use App\Performance\Domain\Model\TeamPerformanceInterface;

class TeamPerformanceReferenceResource
{
    public function __construct(
        public int $teamMultiplier,
        public ?int $position,
        public ?int $points,
        public ?int $score,
    ) {
    }

    public static function fromModel(TeamPerformanceInterface $model): self
    {
        return new self(
            $model->getMultiplier(),
            $model->getPosition(),
            $model->getPoints(),
            $model->getScore(),
        );
    }
}
