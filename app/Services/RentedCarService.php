<?php

namespace App\Services;

use App\Http\Requests\ReturnCarRequest;
use App\Models\Car;
use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use Carbon\Carbon;

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

    public function returnCar(ReturnCarRequest $request) :void
    {
        $carId = $request->car_id;
        $note = $request->note;

        $rentedCar = RentedCar::where('car_id', $carId)->firstOrFail();

        Car::where('id', $carId)->update(['status' => Car::status()]);
        $statistic = Statistics::with("extendedRents")
            ->where("car_id", $carId)
            ->where("created_at", $rentedCar->created_at)
            ->firstOrFail();

        if($statistic->extend_rent){
            // take the last extend the rent and update the return date
            ExtendRent::where("statistics_id", $statistic->id)
                ->where("return_date", $rentedCar->return_date_default_format)
                ->update(["return_date" => Carbon::now()->format("Y-m-d")]);
        }

        $carService = new CarService();

        $statistic->real_return_date = Carbon::now()->format("Y-m-d");
        $statistic->note = $note;
        $statistic->total_price = $carService->calculateTotalPrice($statistic, $rentedCar->return_date);
        $statistic->save();

        $rentedCar->delete();
    }
}
