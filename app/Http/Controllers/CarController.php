<?php

namespace App\Http\Controllers;

use App\Enums\CarStatus;
use App\Handlers\CarHandler;
use App\Models\Car;
use App\Models\RentedCar;
use App\Repository\StatisticsCarsRepository;
use App\Service\CarService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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
            Car::availableCars()
                ->where(function ($query) use ($request) {
                    $query->where('brand', 'LIKE', '%' . $request->query("term") . '%')
                        ->orWhere('license', 'LIKE', '%' . $request->query("term") . '%')
                        ->orWhere('model', 'LIKE', '%' . $request->query("term") . '%');
                })
                ->paginate(Car::$carsPerPage)
        );
    }

    public function filter(Request $request, CarService $carService) :JsonResponse
    {
        return response()->json($carService->filterCars($request));
    }

    /**
     * Store a new car
     */
    public function store(Request $request) :JsonResponse
    {
        $result = CarHandler::createNewCar($request);
        return response()->json(['message'=> $result['message']], $result['statusCode']);
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
    public function update(Request $request) :JsonResponse
    {
        return response()->json(CarHandler::updateCar($request));
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
