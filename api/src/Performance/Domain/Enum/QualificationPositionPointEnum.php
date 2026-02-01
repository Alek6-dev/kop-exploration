<?php

declare(strict_types=1);

namespace App\Performance\Domain\Enum;

enum QualificationPositionPointEnum: int
{
    case P_1 = 15;
    case P_2 = 14;
    case P_3 = 13;
    case P_4 = 12;
    case P_5 = 11;
    case P_6 = 10;
    case P_7 = 9;
    case P_8 = 8;
    case P_9 = 7;
    case P_10 = 6;
    case P_11 = 5;
    case P_12 = 4;
    case P_13 = 3;
    case P_14 = 2;
    case P_15 = 1;
    case P_DEFAULT = 0;

    public static function getPointsFromPosition(string $position): self
    {
        try {
            return self::{'P_'.$position};
        } catch (\Throwable) { // @phpstan-ignore-line
            return self::P_DEFAULT;
        }
    }
}
