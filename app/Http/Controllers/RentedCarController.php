<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Models\User;
use App\Rules\ReasonForDiscount;
use App\Rules\ReturnDate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Collection;
use Psy\Util\Json;

class RentedCarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->getRentedCars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rentCarRules = RentedCar::rules($request->all());
        $rentCarData = [];
        if(!isset($request->all()["user_id"])){
            // new customer
            $rentCarRules = array_merge($rentCarRules, User::rules());
            unset($rentCarRules['user_id']);
            $rentCarData = $request->validate($rentCarRules);
            $user = User::insert([
                "name" => $rentCarData['name'],
                "phone" => $rentCarData['phone'],
                "card_id" => $rentCarData['card_id'],
            ]);
            $rentCarData['user_id'] = $user->id;
        }
        if(empty($rentCarData))
        {
            $rentCarData = $request->validate($rentCarRules);
        }
        $car = Car::where('id', $rentCarData['car_id'])->firstOrFail();
        $rentCarData['price_per_day'] = $car->price_per_day;
        $rentedCar = RentedCar::create($rentCarData);
        $rentCarData['created_at'] = $rentedCar['created_at'];
        $rentCarData['wanted_return_date'] = $rentedCar['return_date'];

        unset($rentCarData['return_date']);
        Statistics::insert($rentCarData);
        $car->update(['status' => RentedCar::status()]);

        return response()->json([
            'message'=> 'Successfully rented car'
        ], 201);
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
            Statistics::update(['total_price' => $this->calculateTotalPrice()]);
            $rentedCar->delete();

            return response()->json([
                'message' => 'Successfully returned car',
            ]);
        }
        return response()->json([
            'message' => 'Bad request',
        ], 400);
    }

    /**
     * Show admin info about total price extend rent, start_date and return date
     */
    public function details(Request $request) :JsonResponse
    {
        $car = Statistics::with('extendedRents')
            ->where('car_id', $request->route('id'))
            ->firstOrFail();

        return response()->json([
                'user_details' => User::where('id', $car->user_id)
                                    ->select('name', 'phone', 'card_id')
                                    ->first(),
                'start_date' => $car->start_date,
                'wanted_return_date' => $car->wanted_return_date,
                'extendedRents' => $car->extendedRents,
        ], 201);
    }

    protected function getRentedCars()
    {
        return RentedCar::with(['user:id,name,phone,card_id,email', 'car:id,license', "extendedRents"])->paginate(RentedCar::$carsPerPage);
    }
}
