<?php

namespace App\Http\Controllers;

use App\Handlers\RentedCarHandler;
use App\Http\Requests\RentCarRequest;
use App\Models\Car;
use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Models\User;
use App\Repository\StatisticsCarsRepository;
use App\Services\CarService;
use App\Services\RentedCarService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        return response()->json(RentedCarRepository::search($request->query("term")), 200);
    }

    public function store(RentCarRequest $request, RentedCarService $rentedCarService) :JsonResponse
    {
        $rentCarData = $request->validated();
        $rentedCarService->rentCar($rentCarData);

        return response()->json([
            "message" => "Successfully rented car"
        ]);
    }

    /**
     * When user returns car or rent is done
     */
    public function return(Request $request)
    {
        $result = RentedCarHandler::returnCar($request);

        return response()->json([
            "message" => $result['message']
        ], $result['statusCode']);
    }

    /**
     * Count rented cars per month or in total
     */
    public function total(Request $request)
    {
        $results = RentedCarHandler::countTotalCars($request);
        return response()->json([
            "total_cars" => $results['totalCars']
        ]);
    }

    /**
     * Latest rented cars
     */
    public function latest()
    {
        return response()->json(RentedCarHandler::latestRented());
    }

}
