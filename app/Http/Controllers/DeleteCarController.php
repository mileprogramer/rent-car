<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCarRequest;
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

    public function delete(DeleteCarRequest $request)
    {
        $carData = $request->validated();
        DeleteCar::create($carData);
        Car::where('id', $carData['car_id'])->update(['status' => DeleteCar::status()]);

        return response()->json(['message' => "You successfully set that car is deleted"]);
    }

}
