<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the cars
     */
    public function index() : Collection
    {
        return Car::all();
    }

    /**
     * Show the cars that have these filters
     */
    public function filter(Request $request)
    {
        $query = Car::query();
        $columnsFilter = [
            "air_conditioning_type",
            "status",
            "transmission_type",
            "model",
            "brand",
            "person_fit_in",
            "year",
            "car_consumption"
        ];

        foreach ($columnsFilter as $column)
        {
            if($request->query($column))
            {
                $query->where($column, $request->query($column));
            }
        }
        return response()->json($query->get(), 200);
    }

    /**
     * Store a new car
     */
    public function store(Request $request)
    {
        $car = $request->validate(Car::rules());
        $createdCar = Car::create($car)->toArray();

        return response()->json(['message'=> 'You successfully add new car', 'car' =>$createdCar], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        return response()->json([
            'car' => $car
        ], 200);
    }


    /**
     * Update the car data
     */
    public function update(Request $request, string $id)
    {
        $car = Car::findOrFail($id);
        $carData = $request->validate(Car::rules());

        $car->update($carData);

        return response()->json([
            'message'=> 'You successfully update car',
            'car' =>$car
        ], 201);
    }

    public function edit(Car $car)
    {
        return response()->json([
            'car' => $car
        ], 200);
    }


    /**
     * Show the statistics of the rentedCars
     */
    public function statistics()
    {
        // code ....
    }
}
