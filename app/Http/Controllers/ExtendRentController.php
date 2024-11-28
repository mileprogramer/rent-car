<?php

namespace App\Http\Controllers;

use App\Actions\FindReturnDate;
use App\Http\Requests\ExtendRentRequest;
use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Services\StatisticsService;
use Carbon\Carbon;

class ExtendRentController extends Controller
{
    public function store (ExtendRentRequest $request, FindReturnDate $findReturnDate)
    {
        $data = $request->validated();
        $rentedCar = RentedCar::with('inStatistics')
            ->where("car_id", $data['car_id'])
            ->firstOrFail();

        Statistics::getStatsForRentedCar($rentedCar->car_id, $rentedCar->created_at)
            ->update(['extend_rent' => true]);

        $data['user_id'] = $rentedCar->user_id;
        $data['start_date'] = Carbon::createFromFormat('Y-m-d', $findReturnDate->find(
            $rentedCar->car_id,
            $rentedCar->inStatistics->id,
            $rentedCar->return_date
        ))->addDay()->format('Y-m-d');
        $data['statistics_id'] = $rentedCar->inStatistics->id;

        ExtendRent::create($data);

        return response()->json([
            'message' => "Successfully extended rent",
        ]);

    }
}
