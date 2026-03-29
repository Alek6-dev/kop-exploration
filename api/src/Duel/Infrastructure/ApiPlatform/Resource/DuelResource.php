<?php

declare(strict_types=1);

namespace App\Duel\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Infrastructure\ApiPlatform\Resource\BonusApplicationResource;
use App\Duel\Application\Dto\SelectDriverDto;
use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Infrastructure\ApiPlatform\State\Processor\SelectDriverDuelProcessor;
use App\Performance\Domain\Enum\DuelPositionPointEnum;
use App\Performance\Infrastructure\ApiPlatform\Resource\DriverPerformanceResource;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\ApiPlatform\Resource\PlayerFlatResource;
use App\Race\Domain\Model\RaceInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Duel',
    operations: [
        new Post(
            uriTemplate: '/championships/{championshipUuid}/duel/select-driver',
            uriVariables: [
                'championshipUuid' => 'string',
            ],
            input: SelectDriverDto::class,
            output: false,
            processor: SelectDriverDuelProcessor::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class DuelResource
{
    /**
     * @param BonusApplicationResource[]|null $bonusesAppliedToPlayer1
     * @param BonusApplicationResource[]|null $bonusesAppliedToPlayer2
     */
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?PlayerFlatResource $player1 = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?PlayerFlatResource $player2 = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverPerformanceResource $playerDriverPerformance1 = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverPerformanceResource $playerDriverPerformance2 = null,
        public ?DuelPositionPointEnum $pointsPlayer1 = null,
        public ?DuelPositionPointEnum $pointsPlayer2 = null,
        public ?int $scorePlayer1 = null,
        public ?int $scorePlayer2 = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BonusApplicationResource $bonusApplicationByPlayer1 = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BonusApplicationResource $bonusApplicationByPlayer2 = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $bonusesAppliedToPlayer1 = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $bonusesAppliedToPlayer2 = null,
    ) {
    }

    public static function fromModel(DuelInterface $model, RaceInterface $race, PlayerInterface $player): self
    {
        $playerDriverPerformance1 = null;
        $playerDriverPerformance2 = null;
        $model->getDriverPerformances()?->map(function (DuelDriverPerformanceInterface $driverPerformance) use ($model, &$playerDriverPerformance1, &$playerDriverPerformance2) {
            if ($driverPerformance->getDriver() === $model->getPlayerDriver1()) {
                $playerDriverPerformance1 = DriverPerformanceResource::fromModel($driverPerformance);
            }
            if ($driverPerformance->getDriver() === $model->getPlayerDriver2()) {
                $playerDriverPerformance2 = DriverPerformanceResource::fromModel($driverPerformance);
            }
        });

        return new self(
            $model->getUuid(),
            $model->getPlayer1() ? PlayerFlatResource::fromModel($model->getPlayer1()) : null,
            $model->getPlayer2() ? PlayerFlatResource::fromModel($model->getPlayer2()) : null,
            $playerDriverPerformance1,
            $playerDriverPerformance2,
            $model->getPointsPlayer1(),
            $model->getPointsPlayer2(),
            $model->getScorePlayer1(),
            $model->getScorePlayer2(),
            $model->getBonusApplicationByPlayer1OnRace($race) ? BonusApplicationResource::fromModel($model->getBonusApplicationByPlayer1OnRace($race)) : null,
            $model->getBonusApplicationByPlayer2OnRace($race) ? BonusApplicationResource::fromModel($model->getBonusApplicationByPlayer2OnRace($race)) : null,
            $model->getBonusAppliedToPlayer1OnRace($race)?->map(fn (BonusApplicationInterface $bonusApplication) => BonusApplicationResource::fromModel($bonusApplication))->toArray(),
            $model->getBonusAppliedToPlayer2OnRace($race)?->map(fn (BonusApplicationInterface $bonusApplication) => BonusApplicationResource::fromModel($bonusApplication))->toArray(),
        );
    }
}
