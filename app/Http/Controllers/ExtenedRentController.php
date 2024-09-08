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
        $data = $request->validate(ExtendRent::rules($request->all()));
        $rentedCar = RentedCar::with('inStatistics')
            ->where("car_id", $data['car_id'])->firstOrFail();
        if(isset($rentedCar->inStatistics))
        {
            $data['statistics_id'] = $rentedCar->inStatistics->id;
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
