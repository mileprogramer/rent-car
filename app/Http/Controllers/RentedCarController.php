<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\RentedCar;
use App\Models\Statistics;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Collection;

class RentedCarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(RentedCar::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(RentedCar::rules($request->all()));
        $rentedCar = RentedCar::create($data);
        $data['created_at'] = $rentedCar['created_at'];
        Statistics::create($data);
        Car::where('id', $data['car_id'])->update(['status' => RentedCar::status()]);

        return response()->json(['message'=> 'Successfully rented car'], 201);
    }

    /**
     * When user returns car, remove the specified car
     */
    public function return(Request $request)
    {
        if(isset($request->all()['car_id']))
        {
            $carId = $request->all()['car_id'];

            $rentedCar = RentedCar::where('car_id', $carId)->firstOrFail();
            Car::where('id', $carId)->update(['status' => Car::status()]);

            $rentedCar->delete();
            return response()->json([
                'message' => 'Successfully returned car',
            ]);
        }
        return response()->json([
            'message' => 'Bad request',
        ], 403);
    }

}
