<?php

declare(strict_types=1);

namespace App\Championship\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class StartChampionshipDto
{
    public function __construct(
        #[Assert\Uuid]
        public string $uuid,
    ) {
    }
}
