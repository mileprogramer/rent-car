<?php

namespace App\Observers;

use App\Models\Car;
use App\Models\RentedCar;

class RentedCarObserver
{
    public function created(RentedCar $rentedCar): void
    {
        Car::where('id', $rentedCar['car_id'])->update(['status' => RentedCar::status()]);
    }
}
