<?php

namespace App\Http\Controllers;

use App\Enums\CarStatus;
use App\Models\Car;
use App\Models\RentedCar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class CarController extends Controller
{
    /**
     * Display a listing of all cars
     */
    public function index()
    {
        return response()->json($this->getCars());
    }

    /**
     * Display a listing of all cars
     */
    public function available()
    {
        return response()->json(Car::where("status", Car::status())->paginate(Car::$carsPerPage));
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
        return response()->json($this->getCars($query), 200);
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

    protected function getCars($query = null)
    {
        $query = $query ?: Car::query();

        $carsStatus = CarStatus::getStatusByOrder();

        $cars = $query->orderByRaw("FIELD(status, $carsStatus)")
            ->paginate(Car::$carsPerPage);

        $cars->getCollection()->transform(function ($car) {
            $car->available = $car->status === Car::status() ? true : false;
            $car->color = CarStatus::getColor($car->status);
            if($car->status === RentedCar::status())
            {
                $car->returned_date = $car->rentedCar?->return_date;
            }
            return $car;
        });

        return $cars;
    }
}
