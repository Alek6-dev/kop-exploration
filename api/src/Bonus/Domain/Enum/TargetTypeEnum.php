<?php

namespace App\Bonus\Domain\Enum;

enum TargetTypeEnum: string
{
    case PLAYER = 'player';
    case SELF = 'self';
}
