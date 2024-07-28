<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum CarStatus :string
{
    use EnumToArray;
    case Broken = 'broken';
    case Sold = 'sold';

    case Rented = 'rented';

    case Available = 'available';

}
