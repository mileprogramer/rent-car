<?php

namespace App\Http\Controllers;

use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExtenedRentController extends Controller
{
    /*
     *
     Extend the rent
     *
    */
    public static function store (Request $request)
    {

        $data = $request->validate(ExtendRent::rules($request->all()));
        $rentedCar = RentedCar::with('inStatistics')
            ->where("car_id", $data['car_id'])
            ->firstOrFail();
        $userReturnDate = Carbon::createFromFormat("d/m/Y", $data['return_date']);
        $rentedCarReturnDate = Carbon::createFromFormat("d/m/Y", $rentedCar->return_date);

        if ($userReturnDate->isBefore($rentedCarReturnDate)){
            abort(429, ['message' => "Chosen return date must be after the before return date"]);
        }

        Statistics::where("car_id", $rentedCar->car_id)
            ->where("created_at", $rentedCar->created_at)
            ->update(['extend_rent' => true]);
        $rentedCar->update([
            "return_date" => $userReturnDate->format("Y-m-d"),
            "price_per_day" => $data['price_per_day'],
        ]);

        $data['user_id'] = $rentedCar->user_id;
        $data['start_date'] = $rentedCarReturnDate->addDay();
        $data['statistics_id'] = $rentedCar->inStatistics->id;

        ExtendRent::create($data);
        return response()->json([
            'message' => "Successfully extended rent",
        ]);

    }
}
