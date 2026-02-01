<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Bid\Domain\Model\BettingRoundDriverInterface;
use App\Driver\Infrastructure\ApiPlatform\Resource\DriverResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'BettingRoundDriver',
    operations: [
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class BettingRoundDriverResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverResource $driver = null,
        #[Assert\Positive]
        public ?int $bidAmount = null,
    ) {
    }

    public static function fromModel(BettingRoundDriverInterface $model): self
    {
        return new self(
            $model->getUuid(),
            DriverResource::fromModel($model->getDriver()),
            $model->getBidAmount(),
        );
    }
}
