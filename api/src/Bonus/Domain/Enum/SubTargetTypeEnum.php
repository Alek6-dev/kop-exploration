<?php

namespace App\Bonus\Domain\Enum;

enum SubTargetTypeEnum: string
{
    case DRIVER_1 = 'driver1';
    case DRIVER_2 = 'driver2';
    case TEAM = 'team';
    //  TODO: Available in v2
    //    case DRIVERS = 'drivers';
}
