<?php

declare(strict_types=1);

namespace App\Strategy\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Bonus\Infrastructure\ApiPlatform\Resource\BonusApplicationResource;
use App\Driver\Infrastructure\ApiPlatform\Resource\DriverResource;
use App\Strategy\Domain\Model\StrategyInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Strategy',
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class PlayerStrategyResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverResource $driver = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BonusApplicationResource $bonusApplication = null,
    ) {
    }

    public static function fromModel(StrategyInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getDriver() ? DriverResource::fromModel($model->getDriver()) : null,
            $model->getBonusApplication() ? BonusApplicationResource::fromModel($model->getBonusApplication()) : null,
        );
    }
}
