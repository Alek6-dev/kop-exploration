<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    operations: [
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class BettingRoundFlatResource
{
    public function __construct(
        public ?string $name = null,
        public ?string $uuidItem = null,
        public ?string $image = null,
        public ?string $color = null,
        public ?int $amount = null,
        public ?int $round = null,
        public ?bool $assignBySystem = false,
    ) {
    }
}
