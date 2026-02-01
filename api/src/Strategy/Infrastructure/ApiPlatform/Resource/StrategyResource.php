<?php

declare(strict_types=1);

namespace App\Strategy\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Infrastructure\ApiPlatform\Resource\BonusApplicationResource;
use App\Driver\Infrastructure\ApiPlatform\Resource\DriverResource;
use App\Performance\Infrastructure\ApiPlatform\Resource\DriverPerformanceResource;
use App\Performance\Infrastructure\ApiPlatform\Resource\TeamPerformanceResource;
use App\Player\Infrastructure\ApiPlatform\Resource\PlayerFlatResource;
use App\Race\Domain\Model\RaceInterface;
use App\Strategy\Application\Dto\SelectDriverDto;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Infrastructure\ApiPlatform\State\Processor\SelectDriverStrategyProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Strategy',
    operations: [
        new Post(
            uriTemplate: '/championships/{championshipUuid}/strategy/select-driver',
            uriVariables: [
                'championshipUuid' => 'string',
            ],
            input: SelectDriverDto::class,
            output: false,
            processor: SelectDriverStrategyProcessor::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class StrategyResource
{
    /**
     * @param DriverPerformanceResource[]|null $driverPerformances
     * @param BonusApplicationResource[]|null  $bonusesApplied
     */
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?PlayerFlatResource $player,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverResource $driver,
        public ?array $driverPerformances,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?TeamPerformanceResource $teamPerformance,
        public ?int $position,
        public ?int $score,
        public ?int $points,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BonusApplicationResource $bonusApplication = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $bonusesApplied = null,
    ) {
    }

    public static function fromModel(StrategyInterface $model, RaceInterface $race): self
    {
        return new self(
            $model->getUuid(),
            PlayerFlatResource::fromModel($model->getPlayer()),
            $model->getDriver() ? DriverResource::fromModel($model->getDriver()) : null,
            $model->getDriverPerformances()->map(fn (StrategyDriverPerformanceInterface $driverPerformance) => DriverPerformanceResource::fromModel($driverPerformance))->toArray(),
            $model->getTeamPerformance() ? TeamPerformanceResource::fromModel($model->getTeamPerformance()) : null,
            $model->getPosition(),
            $model->getScore(),
            $model->getPoints(),
            $model->getBonusApplication() ? BonusApplicationResource::fromModel($model->getBonusApplication()) : null,
            $model->getPlayer()->getStrategyBonusesTargetingCurrentPlayerOnRace($race)
                ->map(fn (BonusApplicationInterface $bonusApplication) => BonusApplicationResource::fromModel($bonusApplication))->toArray(),
        );
    }
}
