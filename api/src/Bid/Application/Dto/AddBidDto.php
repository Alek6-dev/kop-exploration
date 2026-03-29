<?php

declare(strict_types=1);

namespace App\Bid\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class AddBidDto
{
    public function __construct(
        #[Assert\Uuid]
        public ?string $driver1Uuid,
        #[Assert\Positive]
        public ?int $driver1BidAmount,
        #[Assert\Uuid]
        public ?string $driver2Uuid,
        #[Assert\Positive]
        public ?int $driver2BidAmount,
        #[Assert\Uuid]
        public ?string $teamUuid,
        #[Assert\Positive]
        public ?int $teamBidAmount,
    ) {
    }

    #[Assert\IsTrue(message: 'At least one driver or team must be filled.')]
    public function hasAtLeastOneValueIsValid(): bool
    {
        if (!$this->driver1Uuid && !$this->driver2Uuid && !$this->teamUuid) {
            return false;
        }

        return true;
    }
}
