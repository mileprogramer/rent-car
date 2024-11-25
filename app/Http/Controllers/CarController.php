<?php

namespace App\Http\Controllers;

use App\Enums\CarStatus;
use App\Handlers\CarHandler;
use App\Http\Requests\CarImagesRequest;
use App\Http\Requests\DefaultCarRequest;
use App\Models\Car;
use App\Models\RentedCar;
use App\Repository\StatisticsCarsRepository;
use App\Service\CarService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    public function index() :JsonResponse
    {
        $carsStatus = CarStatus::getStatusByOrder();
        $cars = CarService::getCarsWithImages(
            Car::orderByRaw("FIELD(status, $carsStatus)")->with("media")
        );

        return response()->json($cars);
    }

    public function available() :JsonResponse
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

    public function store(DefaultCarRequest $defaultRequest, CarImagesRequest $carImagesRequest, CarService $carService) :JsonResponse
    {
        $carData = $defaultRequest->validate(array_merge(
            $defaultRequest->rules(), $carImagesRequest->rules()
        ));

        $images = Arr::pull($carData, 'images');
        $car = Car::create($carData);
        $carService->storeImagesForCar($car, $images);

        return response()->json([
            'message'=> "New car is created"
        ], 201);
    }

    /**
     * Count available cars
     */
    public function total() :JsonResponse
    {
        return response()->json([
            "total_cars" => CarHandler::countAvailableCars()
        ]);
    }

    /**
     * Update the car data
     */
    public function update(Car $car, CarService $carService, DefaultCarRequest $request) : JsonResponse
    {
        $defaultRules = $request->rules();
        $defaultRules["license"] = Rule::unique("cars")->ignore($car->id);

        $carData = $request->except(['images']);

        if ($request->hasFile('images')) {
            $validatedImages = $request->validate($imageRules);
            $carService->updateImagesForCar($car, $validatedImages['images']);
        }

        $car->update($carData);

        $updatedCar = $car;
        $updatedCar->images = $carService->getImagesForCar($car);

        return response()->json([
            'updatedCar' => $updatedCar,
            'message' => 'Car successfully updated',
        ]);
    }

    /**
     * Search for all cars
     */
    public function search(Request $request) :JsonResponse
    {
        if($request->query("term"))
        {
            return response()->json(CarHandler::searchAllCars($request->query("term")));
        }
        return response()->json([]);
    }
}
