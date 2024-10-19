<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Models\User;
use App\Repositoriums\StatisticsCarsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        return response()->json(RentedCarRepository::getCars());
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
            $rentedCarReturnDate = Carbon::createFromFormat("d/m/Y", $rentedCar->return_date);
            if (empty($note) && $rentedCarReturnDate->isSameDay(Carbon::today()) ) {
                abort(422, ["note"=>'Note must be filled if the user is not returning the car at the date first wanted']);
            }

            Car::where('id', $carId)->update(['status' => Car::status()]);
            $statistic = Statistics::with("extendedRents")
                ->where("car_id", $carId)
                ->where("created_at", $rentedCar->created_at)
                ->firstOrFail();

            if($statistic->extend_rent){
                // take the last extend the rent and update the return date
                ExtendRent::where("statistics_id", $statistic->id)
                    ->where("return_date", $rentedCar->return_date_default_format)
                    ->update(["return_date" => Carbon::now()->format("Y-m-d")]);
            }

            $statistic->real_return_date = Carbon::now()->format("Y-m-d");
            $statistic->note = $note;
            $statistic->total_price = $this->calculateTotalPrice($statistic, $rentedCarReturnDate);
            $statistic->save();

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
     * Count rented cars
     */
    public function total(Request $request)
    {
        if(!$request->has("month")){
            return response()->json(["total_cars"=> RentedCar::count()]);
        }
        $totalCars = StatisticsCarsRepository::rentByMonth();
        if(empty($totalCars)){
            return response()->json(["total_cars" => 0]);
        }
        return response()->json($totalCars[0]);
    }

    /**
     * Latest rented cars
     */
    public function latest()
    {
        return response()->json(RentedCarRepository::latest());
    }

    protected function calculateTotalPrice($statistic, $initialReturnDate) :int
    {
        if(!$statistic->extend_rent){
            $startDate = Carbon::createFromFormat('d/m/Y', trim($statistic->start_date));
            $totalPrice = $startDate->diffInDays(now()) * ($statistic->price_per_day - ($statistic->discount / 100) * $statistic->price_per_day);
            return $totalPrice;
        }

        if($statistic->extend_rent)
        {
            $initialStartDate = Carbon::createFromFormat('d/m/Y', trim($statistic->start_date));
            $firstValue = $initialStartDate->diffInDays($initialReturnDate) * ($statistic->price_per_day - ($statistic->discount / 100) * $statistic->price_per_day);
            $totalPrice = 0;
            $extendedRents = $statistic->extendedRents;

            $updatedExtendedRents = $extendedRents;

            for ($i = $updatedExtendedRents->count() - 1; $i >= 0; $i--) {
                $rent = $updatedExtendedRents[$i];

                // Parse dates using Carbon
                $returnDate = Carbon::createFromFormat('d/m/Y', $rent['return_date']);
                $startDate = Carbon::createFromFormat('d/m/Y', $rent['start_date']);
                $today = Carbon::now();

                if ($returnDate->isAfter($today)) {
                    $updatedExtendedRents[$i]['return_date'] = $today->format('d/m/Y');
                }

                // Check if it's the last element and start_date is after today
                if ($i === $updatedExtendedRents->count() - 1 && $startDate->isAfter($today)) {
                    $updatedExtendedRents->pop();
                }
            }

            foreach ($extendedRents as $extendedRent) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->start_date));
                $returnDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->return_date));

                $totalDays = $returnDate->diffInDays($startDate);
                $totalPriceForRent = $extendedRent->price_per_day * $totalDays;
                $discountedPrice = $totalPriceForRent - ($totalPriceForRent * ($extendedRent->discount / 100));
                $totalPrice += $discountedPrice;
            }

            return $totalPrice + $firstValue;

        }
        return 0;
    }
}
