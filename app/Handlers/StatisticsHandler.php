<?php

namespace App\Handlers;

use App\Models\Car;
use App\Models\Statistics;
use App\Models\User;
use App\Repository\StatisticsCarsRepository;
use App\Service\CarService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StatisticsHandler
{

    public function __construct()
    {

    }

    public function getStats() :LengthAwarePaginator
    {
        return Statistics::with('extendedRents', 'car', 'user')
            ->orderBy("updated_at", "desc")
            ->paginate(Statistics::$perPageStat);
    }

    public function search(Request $request) :LengthAwarePaginator
    {
        $query = Statistics::query();
        $possibleQueries = [
            "start_date" => fn() => $query->where('start_date', ">" ,$request->query('start_date')),
            "end_date" => fn() => $query->where('wanted_return_date', "<" ,$request->query('end_date')),
            "name" => fn() => $query->whereIn('user_id',
                User::where('name', 'like', '%' . $request->query('name') . '%')
                    ->orWhere('card_id', 'like', '%' . $request->query("name") . '%')
                    ->orWhere('phone', 'like', '%' . $request->query("name") . '%')
                    ->pluck('id')),
            "license" => fn() => $query->where('car_id',  Car::where('license', $request->query('license'))->value('id')),
            "extend_rent" => fn() => $request->query('extend_rent') === "true" ?
                $query->where('extend_rent', 1) :
                $query->where('extend_rent', 0),
            "returned_car" => fn() => $request->query('returned_car') === "true" ?
                $query->whereNotNull('real_return_date') :
                $query->whereNull('real_return_date')
        ];

        foreach ($possibleQueries as $key => $statQuery)
        {
            if($request->query($key))
            {
                $statQuery();
            }
        }
        return $query->orderBy("created_at", "desc")
            ->with(['car:id,license', 'user:id,name,phone,card_id'])
            ->paginate(Statistics::$perPageStat);
    }

    public function returnedTotal(Request $request) :array
    {
        if($request->has("month")){
            $totalCars = StatisticsCarsRepository::returnedByMonth();
            if(empty($totalCars))
                return ["total_cars" => 0];

            return ["total_cars" => $totalCars[0]->total_cars];
        }

        return [];
    }

    public function latestReturnedCars() :Collection|LengthAwarePaginator
    {
        return CarService::getCarsWithImages(
            Statistics::select(["start_date", "real_return_date", "total_price", "note" ,"car_id"])
                ->with("car:id,license", "car.media")
                ->whereNotNull("real_return_date")
                ->orderBy("updated_at", "desc"));
    }

}
