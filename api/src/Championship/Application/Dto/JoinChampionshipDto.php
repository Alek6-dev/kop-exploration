<?php

declare(strict_types=1);

namespace App\Championship\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class JoinChampionshipDto
{
    public function __construct(
        #[Assert\Length(min: 3, max: 25)]
        public string $playerName,
    ) {
    }
}
