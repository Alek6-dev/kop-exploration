<?php

declare(strict_types=1);

namespace App\Cosmetic\Domain\Enum;

enum TypeCosmeticEnum: int
{
    case CAR = 1;

    case SUIT = 2;

    case HELMET = 3;
}
