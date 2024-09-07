<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum CarStatus :string
{
    use EnumToArray;
    case Broken = 'broken';
    case Deleted = 'deleted';

    case Rented = 'rented';

    case Available = 'available';

}
