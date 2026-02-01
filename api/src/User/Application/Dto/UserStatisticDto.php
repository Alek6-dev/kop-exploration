<?php

declare(strict_types=1);

namespace App\User\Application\Dto;

class UserStatisticDto
{
    public function __construct(
        public int $countChampionshipsWon,
        public int $countChampionships,
        public int $countDuelsWon,
        public int $countDuels,
        public int $countCosmeticsPossessed,
        public int $countStrategiesWon,
        public int $countStrategies,
    ) {
    }
}
