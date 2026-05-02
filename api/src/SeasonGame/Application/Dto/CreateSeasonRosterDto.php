<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateSeasonRosterDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Count(exactly: 4, exactMessage: 'You must select exactly 4 drivers.')]
        public ?array $driverUuids = null,

        #[Assert\NotBlank]
        #[Assert\Count(exactly: 2, exactMessage: 'You must select exactly 2 teams.')]
        public ?array $teamUuids = null,
    ) {
    }
}
