<?php

declare(strict_types=1);

namespace App\Performance\Domain\Enum\StrategyPosition;

enum HeightPlayersEnum: int
{
    case P_1 = 15;
    case P_2 = 12;
    case P_3 = 9;
    case P_4 = 7;
    case P_5 = 5;
    case P_6 = 3;
    case P_7 = 1;
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
