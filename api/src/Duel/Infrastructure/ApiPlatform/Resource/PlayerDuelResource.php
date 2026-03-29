<?php

declare(strict_types=1);

namespace App\Duel\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Bonus\Infrastructure\ApiPlatform\Resource\BonusApplicationResource;
use App\Driver\Infrastructure\ApiPlatform\Resource\DriverResource;
use App\Player\Infrastructure\ApiPlatform\Resource\PlayerFlatResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'PlayerDuel',
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class PlayerDuelResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverResource $driver = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?PlayerFlatResource $opponent = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BonusApplicationResource $bonusApplication = null,
    ) {
    }
}
