<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Enum;

enum ComparisonEnum: string
{
    case EQUAL = '=';
    case GT = '>';
    case GTE = '>=';
    case LT = '<';
    case LTE = '<=';
}
