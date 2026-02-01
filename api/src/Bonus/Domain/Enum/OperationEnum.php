<?php

namespace App\Bonus\Domain\Enum;

enum OperationEnum: string
{
    case MINUS = '-';
    case PLUS = '+';
    case MULTIPLIER = '*';
    case DIVIDE = '/';
    //  TODO: Available in v2
    //    case LAST_POSITION = 'last_position';
    //    case INVERSION = 'inversion';
    //    case POSITION_EQUAL = 'position_equal';
    //    case COPY = 'copy';
    //    case GAP = 'gap';
    //    case STEAL = 'steal';
}
