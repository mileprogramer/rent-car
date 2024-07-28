<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum AirConditionerType :string
{
    use EnumToArray;
    case Manual = 'manual';
    case Automatic = 'automatic';
    case DualZone = 'dual zone';
    case MultiZone = 'multi zone';
    case RearSeat = 'rear seat';
    case Electric = 'electric';
    case Hybrid = 'hybrid';
    case SolarPowered = 'solar powered';
}
