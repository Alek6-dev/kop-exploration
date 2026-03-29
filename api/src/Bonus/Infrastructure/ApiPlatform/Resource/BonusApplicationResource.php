<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Player\Infrastructure\ApiPlatform\Resource\PlayerFlatResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'BonusApplication',
    operations: [
    ],
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class BonusApplicationResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public string $uuid,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?PlayerFlatResource $player,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BonusResource $bonus,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?PlayerFlatResource $target,
        public ?int $balanceBefore,
        public ?int $balanceAfter,
    ) {
    }

    public static function fromModel(BonusApplicationInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getPlayer() ? PlayerFlatResource::fromModel($model->getPlayer()) : null,
            $model->getBonus() ? BonusResource::fromModel($model->getBonus()) : null,
            $model->getTarget() ? PlayerFlatResource::fromModel($model->getTarget()) : null,
            $model->getBalanceBefore(),
            $model->getBalanceAfter(),
        );
    }
}
