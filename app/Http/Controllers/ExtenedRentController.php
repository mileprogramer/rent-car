<?php

namespace App\Http\Controllers;

use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
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
        // here must be a check does extend rent already exists if yes then on the extend
        // return date must be updated
        $data = $request->validate(ExtendRent::rules($request->all()));
        $rentedCar = RentedCar::with('inStatistics')
            ->where("car_id", $data['car_id'])->firstOrFail();
        // return date from the request must be set in the rented car
        if(isset($rentedCar->inStatistics))
        {
            // does exists ExtendRent::where(statistics_id, ..., return_date, $rentedCar->return_date
            $data['statistics_id'] = $rentedCar->inStatistics->id;
            $data['start_date'] = // return_date from rented car plus one day
            ExtendRent::create($data);
            return response()->json([
                'message' => "Successfully extended rent",
            ]);
        }
        return response()->json([
            'message' => 'Mistake happened, you should contact support, The problem is this car is not in statistics'
        ], 400);
    }
}
