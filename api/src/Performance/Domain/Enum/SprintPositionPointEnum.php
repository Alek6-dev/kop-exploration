<?php

declare(strict_types=1);

namespace App\Performance\Domain\Enum;

enum SprintPositionPointEnum: int
{
    case P_1 = 12;
    case P_2 = 10;
    case P_3 = 8;
    case P_4 = 6;
    case P_5 = 4;
    case P_6 = 3;
    case P_7 = 2;
    case P_8 = 1;
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
