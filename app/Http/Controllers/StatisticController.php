<?php

namespace App\Http\Controllers;

use App\Handlers\StatisticsHandler;
use App\Models\Car;
use App\Models\Statistics;
use App\Models\User;
use App\Repositoriums\StatisticsCarsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index(StatisticsHandler $statisticsHandler)
    {
        return response()->json($statisticsHandler->getStats(), 200);
    }

    public function search(Request $request, StatisticsHandler $statisticsHandler)
    {
        return response()->json(
            $statisticsHandler->search($request)
        );
    }

    /**
     * Count returned cars this (month, year...)
     */
    public function returnedTotal(Request $request)
    {
        if($request->has("month")){
            $totalCars = StatisticsCarsRepository::returnedByMonth();
            if(empty($totalCars))
                return response()->json(["total_cars"=> 0]);

            return response()->json($totalCars[0]);
        }

        if($request->has("year")){
            $totalCars = StatisticsCarsRepository::returnedByYear();
            if(empty($totalCars))
                return response()->json(["total_cars" => 0]);
            return response()->json($totalCars[0]);
        }
        return response()->json([]);
    }

    /**
     * Best selling cars
     */
    public function bestSelling(Request $request)
    {
        $results = DB::table('statistics')
            ->select('car_id', DB::raw('count(*) as number_of_rent'))
            ->groupBy('car_id')
            ->orderBy('number_of_rent', 'desc')
            ->limit(4)
            ->get();

        return response()->json($results);
    }

    public function latest()
    {
        return response()->json(
            StatisticsCarsRepository::getStats(
                Statistics::select(["start_date", "real_return_date", "total_price", "note" ,"car_id"])
                    ->with("car:id,license")
                    ->whereNotNull("real_return_date")
                    ->orderBy("updated_at", "desc")
                    ->limit(Car::$carsPerPage), true, false)
        );
    }
}
