<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Models\User;
use App\Rules\ReasonForDiscount;
use App\Rules\ReturnDate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Psy\Util\Json;
use App\Repositoriums\RentedCarRepository;

class RentedCarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->query("search")){
            return $this->search($request->query("search"));
        }
        return response()->json($this->getRentedCars());
    }

    /**
     * Search for cars.
     */
    public function search($searchTerm)
    {
        return response()->json(RentedCarRepository::search($searchTerm), 200);
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
            $user = User::create([
                "name" => $rentCarData['name'],
                "phone" => $rentCarData['phone'],
                "card_id" => $rentCarData['card_id'],
                "email" => $rentCarData['email'],
                "password" => Hash::make("password"),
                "remember_token" => Str::random(10)
            ]);
            $rentCarData['user_id'] = $user->id;
            unset($rentCarData['name'], $rentCarData['phone'], $rentCarData['card_id'], $rentCarData['email']);
        }
        if(empty($rentCarData))
        {
            // old customer
            $rentCarData = $request->validate($rentCarRules);
        }
        $car = Car::where('id', $rentCarData['car_id'])->firstOrFail();
        $rentCarData['price_per_day'] = $car->price_per_day;
        $rentedCar = RentedCar::create($rentCarData);
        $rentCarData['created_at'] = $rentedCar['created_at'];
        $rentCarData['wanted_return_date'] = $rentedCar['return_date'];

        unset($rentCarData['return_date']);
        Statistics::create($rentCarData);
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
            $note = $request->all()['note'] ?? "";

            $rentedCar = RentedCar::where('car_id', $carId)->firstOrFail();
            if (empty($note) && !$rentedCar->return_date->isSameDay(Carbon::today()) ) {
                abort(422, ["note"=>'Note must be filled if the user is not returning the car at the date first wanted']);
            }

            Car::where('id', $carId)->update(['status' => Car::status()]);
            $statistic = Statistics::where("car_id", $carId)->first();

            if($statistic->extend_rent){
                // take the last extend the rent and update the return date
                ExtendRent::where("statistics_id", $statistic->id)
                    ->where("return_date", $rentedCar->return_date)
                    ->update(["return_date" => $rentedCar->return_date]);
            }

            $statistic->update([
                'total_price' => $this->calculateTotalPrice($statistic),
                'note' => $note,
                "real_return_date" => Carbon::now()->format("Y-m-d")
            ]);
            $rentedCar->delete();

            return response()->json([
                'message' => 'Successfully returned car',
            ], 201);
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

    protected function getRentedCars($query = null)
    {
        if($query === null){
            $query = RentedCar::query();
        }
        return $query->with(['user:id,name,phone,card_id,email', 'car:id,license', "extendedRents"])
            ->orderBy("rented_cars.created_at", "desc")
            ->paginate(RentedCar::$carsPerPage);
    }

    protected function calculateTotalPrice(Statistics $statistic) :int
    {
        $totalPrice = 0;
        if($statistic->extend_rent)
        {
            $extendedRents = $statistic->extendedRents;
            foreach ($extendedRents as $extendedRent)
            {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->start_date));
                $totalPrice += $startDate->diffInDays(now()) * ($extendedRent->price_per_day - ($extendedRent->discount / 100) * $extendedRent->price_per_day);
            }
        }
        else
        {
            $startDate = Carbon::createFromFormat('d/m/Y', trim($statistic->start_date));
            $totalPrice = $startDate->diffInDays(now()) * ($statistic->price_per_day - ($statistic->discount / 100) * $statistic->price_per_day);
        }
        return $totalPrice;
    }
}
