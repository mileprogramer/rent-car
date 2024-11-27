<?php

namespace App\Services;

use App\Models\Car;
use App\Models\RentedCar;
use App\Models\Statistics;

class RentedCarService
{
    public function rentCar(array $rentCarData) :void
    {
        $car = Car::where('id', $rentCarData['car_id'])->firstOrFail();

        $rentCarData['price_per_day'] = $car->price_per_day;
        $rentedCar = RentedCar::create($rentCarData);

        $rentCarData['created_at'] = $rentedCar['created_at'];
        $rentCarData['wanted_return_date'] = $rentedCar['return_date'];
        unset($rentCarData['return_date']);

        Statistics::create($rentCarData);
        $car->update(['status' => RentedCar::status()]);
    }


}
