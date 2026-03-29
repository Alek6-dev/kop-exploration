<?php

declare(strict_types=1);

namespace App\Championship\Domain\Enum;

enum ChampionshipNumberRaceEnum: int
{
    case ONE_RACE = 1;
    case TWO_RACES = 2;
    case THREE_RACES = 3;
    case FOUR_RACES = 4;

    case FIVE_RACES = 5;

    case SIX_RACES = 6;

    case SEVEN_RACES = 7;
    case HEIGHT_RACES = 8;
    case NINE_RACES = 9;
    case TEN_RACES = 10;
}
