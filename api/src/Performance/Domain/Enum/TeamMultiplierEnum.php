<?php

declare(strict_types=1);

namespace App\Performance\Domain\Enum;

enum TeamMultiplierEnum: int
{
    case P_1 = 20;
    case P_2 = 19;
    case P_3 = 18;
    case P_4 = 17;
    case P_5 = 16;
    case P_6 = 15;
    case P_7 = 14;
    case P_8 = 13;
    case P_9 = 12;
    case P_10 = 11;
    case P_DEFAULT = 10;

    public static function getPointsFromPosition(string $position): self
    {
        try {
            return self::{'P_'.$position};
        } catch (\Throwable) { // @phpstan-ignore-line
            return self::P_DEFAULT;
        }
    }
}
