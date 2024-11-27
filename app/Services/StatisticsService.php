<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Statistics;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsService
{
    public function search(Request $request)
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
            ->with(['car:id,license', 'user:id,name,phone,card_id', "extendedRents"])
            ->paginate(Statistics::$perPageStat);
    }
}
