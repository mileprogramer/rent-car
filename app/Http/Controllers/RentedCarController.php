<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentCarRequest;
use App\Http\Requests\ReturnCarRequest;
use App\Models\RentedCar;
use App\Repository\StatisticsCarsRepository;
use App\Services\CarService;
use App\Services\RentedCarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repository\RentedCarRepository;

class RentedCarController extends Controller
{
    public function index() : JsonResponse
    {
        return response()->json(
            RentedCar::with("car:id,license", "user:id,name,phone,card_id")
                ->orderBy("created_at", "desc")
                ->paginate(RentedCar::$carsPerPage)
        );
    }
    public function search(Request $request) : JsonResponse
    {
        if($request->query("term"))
        {
            return response()->json(RentedCarRepository::search($request->query("term")), 200);
        }
        abort(404);
    }

    public function store(RentCarRequest $request, RentedCarService $rentedCarService) :JsonResponse
    {
        $rentCarData = $request->validated();
        $rentedCarService->rentCar($rentCarData);

        return response()->json([
            "message" => "Successfully rented car"
        ]);
    }

    public function return(ReturnCarRequest $request, RentedCarService $rentedCarService)
    {
        $carData = $request->validated();
        $rentedCarService->returnCar($carData);

        return response()->json([
            "message" => "Successfully returned car"
        ]);
    }
    public function countTotalCars(Request $request) :JsonResponse
    {
        if(!$request->has("month")){
            return response()->json([
                "totalCars" => RentedCar::count()
            ]);
        }
        $queryResult = StatisticsCarsRepository::rentByMonth();
        if(empty($queryResult)){
            return response()->json([
                "totalCars" => 0
            ]);
        }
        return response()->json([
            "totalCars" => $queryResult[0]->total_cars
        ]);
    }

    public function latest(CarService $carService)
    {
        return response()->json(
            $carService->getCarsWithImages(
                RentedCar::select(["start_date", "return_date", "price_per_day", "car_id", "user_id"])
            )
        );
    }

}
