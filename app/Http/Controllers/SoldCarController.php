<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\SoldCar;
use Illuminate\Http\Request;

class SoldCarController extends Controller
{
    public function sell(Request $request)
    {
        $carData = $request->validate(SoldCar::rules());
        SoldCar::create($carData);
        Car::where('id', $carData['car_id'])->update(['status' => 'sold']);

        return response()->json(['message' => "You successfully set that car is sold"]);
    }

    /**
     * Display the sold cars
     */
    public function sold()
    {
        return response()->json(SoldCar::paginate());
    }
}
