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


    public static function getStatusByOrder() :string
    {
        $carStatus = [
            CarStatus::Available->value,
            CarStatus::Rented->value,
            CarStatus::Broken->value,
            CarStatus::Deleted->value,
        ];
        return "'" . implode("', '", $carStatus) . "'";
    }

    public static function getColor(string $carStatus) :string
    {
        $colors = [
            CarStatus::Available->value => "success",
            CarStatus::Rented->value => "rented",
            CarStatus::Broken->value => "broken",
            CarStatus::Deleted->value => "deleted",
        ];
        return $colors[$carStatus];
    }
}
