<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\DeleteCar;
use Illuminate\Http\Request;

class DeleteCarController extends Controller
{

    /**
     * Display the deleted cars
     */
    public function index()
    {
        return response()->json(DeleteCar::paginate());
    }

    public function delete(Request $request)
    {
        $carData = $request->validate(DeleteCar::rules());
        DeleteCar::create($carData);
        Car::where('id', $carData['car_id'])->update(['status' => DeleteCar::status()]);

        return response()->json(['message' => "You successfully set that car is deleted"]);
    }

}
