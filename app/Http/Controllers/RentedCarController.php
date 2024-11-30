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
    public function getRentedCars() : JsonResponse
    {
        return response()->json(
            RentedCar::with("car:id,license", "user:id,name,phone,card_id")
                ->orderBy("created_at", "desc")
                ->paginate(RentedCar::$carsPerPage)
        );
    }
    public function searchRentedCars(Request $request, RentedCarRepository $rentedCarRepository) : JsonResponse
    {
        if($request->query("term"))
        {
            return response()->json($rentedCarRepository->search($request->query("term")), 200);
        }
        abort(404);
    }

    public function rentCar(RentCarRequest $request, RentedCarService $rentedCarService) :JsonResponse
    {
        $rentCarData = $request->validated();
        $rentedCarService->rentCar($rentCarData);

        return response()->json([
            "message" => "Successfully rented car"
        ]);
    }

    public function returnCar(ReturnCarRequest $request, RentedCarService $rentedCarService)
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
                "total_cars" => RentedCar::count()
            ]);
        }
        $queryResult = StatisticsCarsRepository::rentByMonth();
        if(empty($queryResult)){
            return response()->json([
                "total_cars" => 0
            ]);
        }
        return response()->json([
            "total_cars" => $queryResult[0]->total_cars
        ]);
    }

    public function latestRentedCars(CarService $carService)
    {
        return response()->json(
            $carService->getCarsWithImages(
                RentedCar::select(["start_date", "return_date", "price_per_day", "car_id", "user_id"])
            )
        );
    }

}
