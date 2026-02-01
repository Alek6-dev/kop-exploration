<?php

declare(strict_types=1);

namespace App\Championship\Application\Dto;

use App\Championship\Domain\Enum\ChampionshipNumberPlayerEnum;
use App\Championship\Domain\Enum\ChampionshipNumberRaceEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CreateChampionshipDto
{
    public function __construct(
        #[Assert\Length(min: 3, max: 30)]
        public string $name,
        #[Assert\Length(min: 3, max: 25)]
        public string $playerName,
        #[Assert\NotNull]
        public bool $jokerEnabled,
        #[Assert\NotNull]
        public ChampionshipNumberRaceEnum $championshipNumberRace,
        #[Assert\NotNull]
        public ChampionshipNumberPlayerEnum $championshipNumberPlayer,
    ) {
    }
}
