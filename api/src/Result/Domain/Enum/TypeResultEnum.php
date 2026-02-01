<?php

declare(strict_types=1);

namespace App\Result\Domain\Enum;

enum TypeResultEnum: int
{
    case NORMAL = 1;

    case QUALIFICATION = 2;

    case SPRINT = 3;
}
