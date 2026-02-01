<?php

declare(strict_types=1);

namespace App\Performance\Domain\Enum;

enum DuelPositionPointEnum: int
{
    case P_1 = 5;
    case P_2 = 0;
    case P_DEFAULT = 3;

    public static function getPointsFromPosition(int $position): self
    {
        try {
            return self::{'P_'.$position};
        } catch (\Throwable) { // @phpstan-ignore-line
            return self::P_DEFAULT;
        }
    }
}
