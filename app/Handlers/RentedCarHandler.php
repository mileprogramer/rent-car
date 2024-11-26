<?php

namespace App\Handlers;

use App\Models\Car;
use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Models\User;
use App\Repository\RentedCarRepository;
use App\Repository\StatisticsCarsRepository;
use App\Service\CarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RentedCarHandler
{

    public static function getCars() : LengthAwarePaginator
    {
        return RentedCar::with("car:id,license", "user:id,name,phone,card_id")
            ->orderBy("created_at", "desc")
            ->paginate(RentedCar::$carsPerPage);
    }

    public static function search($searchTerm) : LengthAwarePaginator
    {
        return RentedCarRepository::search($searchTerm);
    }

    public static function store(Request $request) : array
    {
        $rentCarData = $request->all();
        if(!isset($rentCarData["user_id"])){
            // new customer
            $user = UserHandler::addNewUser([
                "name" => $rentCarData['name'],
                "phone" => $rentCarData['phone'],
                "card_id" => $rentCarData['card_id'],
                "email" => $rentCarData['email'],
            ]);
            $rentCarData['user_id'] = $user->id;
            unset($rentCarData['name'], $rentCarData['phone'], $rentCarData['card_id'], $rentCarData['email']);
        }
        if(empty($rentCarData))
        {
            // old customer
            $rentCarData = $request->validate(RentedCar::rules());
        }
        $car = Car::where('id', $rentCarData['car_id'])->firstOrFail();
        $rentCarData['price_per_day'] = $car->price_per_day;
        $rentedCar = RentedCar::create($rentCarData);
        $rentCarData['created_at'] = $rentedCar['created_at'];
        $rentCarData['wanted_return_date'] = $rentedCar['return_date'];

        unset($rentCarData['return_date']);
        Statistics::create($rentCarData);
        $car->update(['status' => RentedCar::status()]);

        return [
            "message" => "Successfully rented car",
            "statusCode" => 201
        ];

    }

    public static function returnCar(Request $request) : array
    {
        if(isset($request->all()['car_id']))
        {
            $carId = $request->all()['car_id'];
            $note = $request->all()['note'] ?? "";

            $rentedCar = RentedCar::where('car_id', $carId)->firstOrFail();
            $rentedCarReturnDate = Carbon::createFromFormat("d/m/Y", $rentedCar->return_date);
            if (empty($note) && $rentedCarReturnDate->isSameDay(Carbon::today()) ) {
                return [
                    "message" => "Note must be filled if the user is not returning the car at the date first wanted",
                    "statusCode" => 422,
                ];
            }

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
            $statistic->total_price = $carService->calculateTotalPrice($statistic, $rentedCarReturnDate);
            $statistic->save();

            $rentedCar->delete();

            return [
                'message' => 'Successfully returned car',
                "statusCode" => 201
            ];
        }
        return [
            'message' => 'Bad request',
            "statusCode" => 400
        ];
    }

    public static function countTotalCars(Request $request) :array
    {
        if(!$request->has("month")){
            return [
                "totalCars" => RentedCar::count()
            ];
        }
        $queryResult = StatisticsCarsRepository::rentByMonth();
        if(empty($queryResult)){
            return [
                "totalCars" => 0
            ];
        }
        return [
            "totalCars" => $queryResult[0]->total_cars
        ];
    }

    public static function latestRented(CarService $carService) :LengthAwarePaginator|Collection
    {
        return $carService->getCarsWithImages(
            RentedCar::select(["start_date", "return_date", "price_per_day", "car_id", "user_id"])
        );
    }

}
