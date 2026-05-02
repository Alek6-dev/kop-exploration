<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class SaveSeasonGPStrategyDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public ?string $driver1Uuid = null,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public ?string $driver2Uuid = null,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public ?string $teamUuid = null,
    ) {
    }
}
