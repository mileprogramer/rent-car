<?php

namespace App\Http\Controllers;

use App\Handlers\StatisticsHandler;
use App\Models\Car;
use App\Models\Statistics;
use App\Models\User;
use App\Repository\StatisticsCarsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index(StatisticsHandler $statisticsHandler)
    {
        return response()->json($statisticsHandler->getStats());
    }

    public function search(Request $request, StatisticsHandler $statisticsHandler)
    {
        return response()->json(
            $statisticsHandler->search($request)
        );
    }

    /**
     * Count returned cars this month
     */
    public function returnedTotal(Request $request, StatisticsHandler $statisticsHandler)
    {
        return response()->json(
            $statisticsHandler->returnedTotal($request)
        );
    }

    /**
     * Best selling cars TO DO
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

    public function latest(StatisticsHandler $statisticsHandler)
    {
        return response()->json(
            $statisticsHandler->latestReturnedCars()
        );
    }
}
