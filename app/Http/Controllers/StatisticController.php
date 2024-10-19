<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Statistics;
use App\Models\User;
use App\Repositoriums\StatisticsCarsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index()
    {
        return response()->json(StatisticsCarsRepository::getStats(
            Statistics::with('extendedRents', 'car', 'user')
                ->orderBy("updated_at", "desc")
            , false, true)
        );
    }

    public function search(Request $request)
    {
        $query = Statistics::query();

        if ($request->query('start_date')) {
            $query->where('start_date', ">" ,$request->query('start_date'));
        }

        if ($request->query('end_date')) {
            $query->where('wanted_return_date', "<" ,$request->query('end_date'));
        }

        if ($request->query('name')) {
            $query->whereIn('user_id',
                User::where('name', 'like', '%' . $request->query('name') . '%')
                    ->orWhere('card_id', 'like', '%' . $request->query("name") . '%')
                    ->orWhere('phone', 'like', '%' . $request->query("name") . '%')
                    ->pluck('id'));
        }

        if ($request->query('license')) {
            $query = $query->where('car_id',  Car::where('license', $request->query('license'))->value('id'));
        }

        if ($request->query('extend_rent')) {
            $request->query('extend_rent') === "true" ?
                $query->where('extend_rent', 1) :
                $query->where('extend_rent', 0);
        }

        if ($request->query('returned_car')) {
            $request->query('returned_car') === "true" ?
                $query->whereNotNull('real_return_date') :
                $query->whereNull('real_return_date');
        }

        return response()->json(
            $query->orderBy("created_at", "desc")
                ->with(['car:id,license', 'user:id,name,phone,card_id'])
                ->paginate()
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
