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
    public function index(Request $request)
    {
        if($request->query("search")){
            return $this->search($request->query("search"));
        }
        return response()->json($this->getCars());
    }

    /**
     * Display a listing of all cars
     */
    public function available(Request $request)
    {
        if(($request->query("search"))){
            return $this->search($request->query("search"), Car::status());
        }
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
        Car::create($car);

        return response()->json(['message'=> 'You successfully add new car'], 201);
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
    public function update(Request $request)
    {
        $rules = Car::rules();
        $rules["license"] = array_filter($rules, fn($rule) => $rule !==  "unique:cars");
        $rules['id'] = ["required", "numeric"];

        $carData = $request->validate($rules);
        $car = Car::findOrFail($request->input("id"));

        $car->update($carData);

        return response()->json([
            'message'=> 'You successfully update car',
        ], 201);
    }

    public function search(string $searchTerm, string $carStatus = "")
    {
        if($carStatus === "")
        {
            // search for all cars
            return response()->json(
                $this->getCars(
                    Car::query()
                        ->where(function ($query) use ($searchTerm) {
                            $query->where('brand', 'LIKE', '%' . $searchTerm . '%')
                                ->orWhere('license', 'LIKE', '%' . $searchTerm . '%')
                                ->orWhere('model', 'LIKE', '%' . $searchTerm . '%');
                        })
                ),
            200);
        }
        return response()->json(
            Car::where('status', $carStatus)
                ->where(function ($query) use ($searchTerm) {
                    $query->where('brand', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('license', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('model', 'LIKE', '%' . $searchTerm . '%');
                })
                ->paginate(Car::$carsPerPage),
        200);
    }

    protected function getCars($query = null)
    {
        $query = $query ?: Car::query();

        $carsStatus = CarStatus::getStatusByOrder();

        $cars = $query->orderByRaw("FIELD(status, $carsStatus)")
            ->paginate(Car::$carsPerPage);

        $cars->getCollection()->transform(function ($car) {
            $car->color = CarStatus::getColor($car->status);
            $car->last_time_updated = $car->updated_at !== $car->created_at ? $car->updated_at : null;
            return $car;
        });

        return $cars;
    }
}
