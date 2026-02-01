<?php

declare(strict_types=1);

namespace App\Parameter\Domain\Enum;

enum TypeEnum: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case BOOL = 'bool';
}
