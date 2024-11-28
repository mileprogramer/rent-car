<?php

namespace App\Actions;

use App\Models\ExtendRent;
use Carbon\Carbon;

class FindReturnDate
{
    public function find(int $carId, $statisticsId, $returnDate) :string
    {
        $extendedRents = ExtendRent::getExtendedRentsForRent($carId, $statisticsId)->get();

        return $extendedRents->isEmpty() ?
            Carbon::createFromFormat('d/m/Y', $returnDate)->format('Y-m-d') :
            Carbon::createFromFormat('d/m/Y', $extendedRents->last()->return_date)->format('Y-m-d');
    }
}
