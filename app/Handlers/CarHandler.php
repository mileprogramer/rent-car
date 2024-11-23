<?php

namespace App\Handlers;

use App\Enums\CarStatus;
use App\Models\Car;
use App\Service\CarService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;

class CarHandler
{
    public static function getAllCars()
    {
        $carsStatus = CarStatus::getStatusByOrder();
        $cars = CarService::getCarsWithImages(Car::orderByRaw("FIELD(status, $carsStatus)")->with("media"));

        $cars->setCollection(
            $cars->getCollection()->map(function ($car) {
                $car->last_time_updated = $car->updated_at !== $car->created_at ? $car->updated_at : null;
                return $car;
            })
        );

        return $cars;
    }

    public static function getAvailableCars() :LengthAwarePaginator
    {
        return Car::where("status", Car::status())
            ->paginate(Car::$carsPerPage);
    }

    public static function searchAllCars(string $searchTerm) :LengthAwarePaginator
    {
        return CarService::getCarsWithImages(Car::query()
            ->where(function ($query) use ($searchTerm) {
                $query->where('brand', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('license', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('model', 'LIKE', '%' . $searchTerm . '%');
            }));
    }

    public static function searchAvailableCars(string $searchTerm) :LengthAwarePaginator
    {
        return Car::where('status', Car::status())
            ->where(function ($query) use ($searchTerm) {
                $query->where('brand', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('license', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('model', 'LIKE', '%' . $searchTerm . '%');
            })
            ->paginate(Car::$carsPerPage);
    }

    public static function filterCars(Request $request) : LengthAwarePaginator
    {
        $query = Car::query();
        $columnsFilter = [
            "air_conditioning_type",
            "status",
            "transmission_type",
            "model",
            "brand",
            "person_fit_in",
            "year",
            "car_consumption"
        ];

        foreach ($columnsFilter as $column)
        {
            if($request->query($column))
            {
                $query->where($column, $request->query($column));
            }
        }
        return $query->paginate(Car::$carsPerPage);
    }

    public static function createNewCar(Request $request) : array
    {
        $carData = $request->validate(Car::rules());
        if($request->hasFile("images") === false)
            return [
                'message'=>"Images are required",
                "statusCode"=> 422,
            ];

        unset($carData['images']);
        $car = Car::create($carData);
        CarService::storeImagesForCar($car, $request->file("images"));
        return [
            "message" => "New car is created",
            "statusCode" => 201,
        ];
    }

    public static function updateCar(Request $request) : array
    {
        $rules = Car::rules();
        $rules["license"] = Rule::unique("cars")->ignore($request->all()['id']);
        $rules['id'] = ["required", "numeric"];

        $carData = $request->validate($rules);
        $car = Car::findOrFail($request->input("id"));

        if($request->hasFile("images"))
        {
            CarService::updateImagesForCar($car, $request->file("images"));
        }

        unset($carData['images']);
        $car->update($carData);
        $updatedCar = $car;
        $updatedCar->images = CarService::getImagesForCar($car);

        return [
            "updatedCar" => $updatedCar,
            "message" => "Car is successfully updated"
        ];
    }

    public static function countAvailableCars() :int
    {
        return Car::where("status", Car::status())
            ->count();
    }
}
