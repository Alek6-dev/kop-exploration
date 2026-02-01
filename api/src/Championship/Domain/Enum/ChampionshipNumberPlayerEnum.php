<?php

declare(strict_types=1);

namespace App\Championship\Domain\Enum;

enum ChampionshipNumberPlayerEnum: int
{
    case FOUR_PLAYERS = 4;
    case SIX_PLAYERS = 6;

    case HEIGHT_PLAYERS = 8;
    case TEN_PLAYERS = 10;
}
