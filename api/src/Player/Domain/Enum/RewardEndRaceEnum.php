<?php

declare(strict_types=1);

namespace App\Player\Domain\Enum;

enum RewardEndRaceEnum: int
{
    case P_1 = 10;
    case P_2 = 9;
    case P_3 = 8;
    case P_4 = 7;
    case P_5 = 6;
    case P_6 = 5;
    case P_7 = 4;
    case P_8 = 3;
    case P_9 = 2;
    case P_10 = 1;
    case P_DEFAULT = 0;

    public static function getPointsFromPosition(int $position): self
    {
        try {
            return self::{'P_'.$position};
        } catch (\Throwable) { // @phpstan-ignore-line
            return self::P_DEFAULT;
        }
    }
}
