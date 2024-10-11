<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Statistics;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index()
    {
        return response()->json(Statistics::with('extendedRents', 'car', 'user')
            ->orderBy("created_at", "desc")
            ->paginate(Car::$carsPerPage));
    }

    public function search(Request $request)
    {
        $query = Statistics::query();
        $hasFilters = false;

        if ($request->query('start_date')) {
            $query->where('start_date', ">" ,$request->query('start_date'));
            $hasFilters = true;
        }

        if ($request->query('end_date')) {
            $query->where('wanted_return_date', "<" ,$request->query('end_date'));
            $hasFilters = true;
        }

        if ($request->query('name')) {
            $hasFilters = true;
            $query->whereIn('user_id',
                User::where('name', 'like', '%' . $request->query('name') . '%')
                    ->orWhere('card_id', 'like', '%' . $request->query("name") . '%')
                    ->orWhere('phone', 'like', '%' . $request->query("name") . '%')
                    ->pluck('id'));
        }

        if ($request->query('license')) {
            $hasFilters = true;
            $query = $query->where('car_id',  Car::where('license', $request->query('license'))->value('id'));
        }

        if ($request->query('extend_rent')) {
            $hasFilters = true;
            $request->query('extend_rent') === "true" ?
                $query->where('extend_rent', 1) :
                $query->where('extend_rent', 0);
        }

        if ($request->query('returned_car')) {
            $hasFilters = true;
            $request->query('returned_car') === "true" ?
                $query->whereNotNull('real_return_date') :
                $query->whereNull('real_return_date');
        }

//        if($hasFilters)
//        {
//            return response()->json(
//                $query->orderBy("created_at", "desc")
//                    ->with(['car:id,license', 'user:id,name,phone,card_id'])
//                    ->paginate()
//            );
//        }

        return response()->json(
            $query->orderBy("created_at", "desc")
                ->with(['car:id,license', 'user:id,name,phone,card_id'])
                ->paginate()
        );
    }
}
