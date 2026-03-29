<?php

declare(strict_types=1);

namespace App\Performance\Domain\Enum\StrategyPosition;

enum TenPlayersEnum: int
{
    case P_1 = 15;
    case P_2 = 12;
    case P_3 = 10;
    case P_4 = 8;
    case P_5 = 6;
    case P_6 = 4;
    case P_7 = 3;
    case P_8 = 2;
    case P_9 = 1;
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
