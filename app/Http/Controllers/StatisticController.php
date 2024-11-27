<?php

namespace App\Http\Controllers;

use App\Handlers\StatisticsHandler;
use App\Models\Car;
use App\Models\Statistics;
use App\Models\User;
use App\Repository\StatisticsCarsRepository;
use App\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index()
    {
        return response()->json(StatisticsHandler::getStats());
    }

    public function search(Request $request)
    {
        return response()->json(
            StatisticsHandler::search($request)
        );
    }

    public function returnedTotal(Request $request) :JsonResponse
    {
        if($request->has("month")){
            $totalCars = StatisticsCarsRepository::returnedByMonth();
            if(empty($totalCars))
                return response()->json(
                    ["total_cars" => 0]
                );

            return response()->json([
               "total_cars" => $totalCars[0]->total_cars
            ]);
        }

        abort(404);
    }

    public function latest(CarService $carService)
    {
        return response()->json(
            $carService->getCarsWithImages(
                Statistics::select(["start_date", "real_return_date", "total_price", "note" ,"car_id"])
                    ->with("car:id,license", "car.media")
                    ->whereNotNull("real_return_date")
                    ->orderBy("updated_at", "desc")
            )
        );
    }
}
