<?php

namespace App\Http\Controllers;

use App\Enums\CarStatus;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CarController extends Controller
{
    public function getCars(CarService $carService) :JsonResponse
    {
        $carsStatus = CarStatus::getStatusByOrder();
        $cars = $carService->getCarsWithImages(
            Car::orderByRaw("FIELD(status, $carsStatus)")->with("media")
        );

        return response()->json($cars);
    }

    public function availableCars() :JsonResponse
    {
        return response()->json(
            Car::availableCars()
                ->paginate(Car::$carsPerPage)
        );
    }

    public function searchAvailable(Request $request) :JsonResponse
    {
        if(!($request->query("term"))){
            abort(404);
        }
        return response()->json(
            Car::searchavailableCars($request->query("term"))
                ->paginate(Car::$carsPerPage)
        );
    }

    public function createNewCar(StoreCarRequest $request, CarService $carService) :JsonResponse
    {
        $carData = $request->validated();

        $images = Arr::pull($carData, 'images');
        $car = Car::create($carData);
        $carService->storeImagesForCar($car, $images);

        return response()->json([
            'message'=> "New car is created"
        ], 201);
    }

    public function totalAvailableCars() :JsonResponse
    {
        return response()->json([
            "total_cars" => Car::totalAvailableCars()
        ]);
    }

    public function updateCar(CarService $carService, UpdateCarRequest $request) : JsonResponse
    {
        $car = Car::where("id", $request->id)->firstOrFail();
        $carData = $request->validated();

        if($request->hasFile("images"))
        {
          $carService->updateImagesForCar($car, $request->images);
          unset($carData['images']);
        }

        $car->update($carData);
        $updatedCar = $car;
        $updatedCar->images = $car->car_images;

        return response()->json([
            'updatedCar' => $updatedCar,
            'message' => 'Car successfully updated',
        ]);
    }

    public function searchCars(Request $request, CarService $carService) :JsonResponse
    {
        if($request->query("term"))
        {
            return response()->json(
                $carService->getCarsWithImages(
                    Car::searchCars($request->query("term"))
                )
            );
        }
        abort(404);
    }
}
