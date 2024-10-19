<?php

namespace App\Http\Controllers;

use App\Enums\CarStatus;
use App\Models\Car;
use App\Models\RentedCar;
use App\Repositoriums\StatisticsCarsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        return response()->json($this->getCars(Car::query()->where("status", Car::status())));
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
        $carData = $request->validate(Car::rules());
        if($request->hasFile("images") === false)
            return response()->json(['message'=>"Images are required", "images"=> $request->all()["images"]], 422);

        $car = Car::create($carData);
        foreach ($request->file('images') as $image) {
            $car->addMedia($image)->toMediaCollection('cars_images');
        }

        return response()->json(['message'=> 'You successfully add new car'], 201);
    }

    /**
     * Count available cars
     */
    public function total()
    {
        return response()->json([
            "total_cars" => Car::where("status", Car::status())->count()
        ]);
    }

    /**
     * Update the car data
     */
    public function update(Request $request)
    {
        $rules = Car::rules();
        $rules["license"] = Rule::unique("cars")->ignore($request->all()['id']);
        $rules['id'] = ["required", "numeric"];

        $carData = $request->validate($rules);
        $car = Car::findOrFail($request->input("id"));

        if($request->hasFile("images"))
        {
            $car->clearMediaCollection('cars_images');

            foreach ($request->file('images') as $image) {
                $car->addMedia($image)->toMediaCollection('cars_images');
            }
        }

        unset($carData['images']);
        $car->update($carData);
        $updatedCar = $car;
        $updatedCar->images = $car->imagesUrl;

        return response()->json([
            'updatedCar' => $updatedCar,
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
            $car->images = $car->images_url;
            return $car;
        });

        return $cars;
    }
}
