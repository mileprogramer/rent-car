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

    public $data = [];

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
        return $query->with(['user:id,name,phone,card_id,email', 'car:id,license,images'])
            ->orderBy("rented_cars.created_at", "desc")
            ->paginate(RentedCar::$carsPerPage);
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
                    // Update the return_date to today's date
                    $updatedExtendedRents[$i]['return_date'] = $today->format('d/m/Y');
                }

                // Check if it's the last element and start_date is after today
                if ($i === $updatedExtendedRents->count() - 1 && $startDate->isAfter($today)) {
                    $updatedExtendedRents->pop();
                }
            }

            foreach ($extendedRents as $extendedRent) {
                // Parse the start and return dates
                $startDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->start_date));
                $returnDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->return_date));

                // Calculate the total number of days
                $totalDays = $returnDate->diffInDays($startDate);

                // Calculate the total price based on price per day and total days
                $totalPriceForRent = $extendedRent->price_per_day * $totalDays;

                // Apply discount
                $discountedPrice = $totalPriceForRent - ($totalPriceForRent * ($extendedRent->discount / 100));

                // Accumulate the discounted price into the total price
                $totalPrice += $discountedPrice;
            }

            return $totalPrice + $firstValue;

        }
        return 0;
    }
}
