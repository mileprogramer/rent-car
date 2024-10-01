<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum TransmissionType :string
{
    use EnumToArray;
 case Automatic = 'automatic';
 case Manual = 'manual';
 case SemiAutomatic = 'semi-automatic';

}
