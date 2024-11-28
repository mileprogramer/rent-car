<?php

namespace App\Services;

use App\Models\Car;
use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

    function calculateTotalPrice(Statistics|Builder $statistic) :int
    {
        if(!$statistic->extend_rent){
            $startDate = Carbon::createFromFormat('d/m/Y', trim($statistic->start_date));
            $totalPrice = $startDate->diffInDays(now()) * ($statistic->price_per_day - ($statistic->discount / 100) * $statistic->price_per_day);
            return $totalPrice;
        }

        if($statistic->extend_rent)
        {
            $initialStartDate = Carbon::createFromFormat('d/m/Y', trim($statistic->start_date));
            $firstValue = $initialStartDate->diffInDays(now()) * ($statistic->price_per_day - ($statistic->discount / 100) * $statistic->price_per_day);
            $totalPrice = 0;
            $extendedRents = $statistic->extendedRents;

            $updatedExtendedRents = $extendedRents;

            for ($i = $updatedExtendedRents->count() - 1; $i >= 0; $i--) {
                $rent = $updatedExtendedRents[$i];

                $returnDate = Carbon::createFromFormat('d/m/Y', $rent['return_date']);
                $startDate = Carbon::createFromFormat('d/m/Y', $rent['start_date']);
                $today = Carbon::now();

                if ($returnDate->isAfter($today)) {
                    $updatedExtendedRents[$i]['return_date'] = $today->format('d/m/Y');
                }

                if ($i === $updatedExtendedRents->count() - 1 && $startDate->isAfter($today)) {
                    $updatedExtendedRents->pop();
                }
            }

            foreach ($extendedRents as $extendedRent) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->start_date));
                $returnDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->return_date));

                $totalDays = $returnDate->diffInDays($startDate);
                $totalPriceForRent = $extendedRent->price_per_day * $totalDays;
                $discountedPrice = $totalPriceForRent - ($totalPriceForRent * ($extendedRent->discount / 100));
                $totalPrice += $discountedPrice;
            }

            return $totalPrice + $firstValue;

        }
        return 0;
    }
}
