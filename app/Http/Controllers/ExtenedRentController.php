<?php

namespace App\Http\Controllers;

use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExtenedRentController extends Controller
{
    /**
     Extend the rent
     **/
    public static function store (Request $request)
    {
        $data = $request->validate(ExtendRent::rules($request->all()));
        $rentedCar = RentedCar::with('inStatistics')
            ->where("car_id", $data['car_id'])
            ->firstOrFail();
        $userReturnDate = Carbon::createFromFormat("Y-m-d", $data['return_date']);

        if ($userReturnDate->isBefore($data['return_date'])){
            abort(429, ['message' => "Chosen return date must be after ex return date"]);
        }

        Statistics::where("car_id", $rentedCar->car_id)
            ->where("created_at", $rentedCar->created_at)
            ->update([
                'extend_rent' => true,
            ]);

        $data['user_id'] = $rentedCar->user_id;
        $data['start_date'] = self::findReturnDay($rentedCar);
        $data['statistics_id'] = $rentedCar->inStatistics->id;

        ExtendRent::create($data);
        return response()->json([
            'message' => "Successfully extended rent",
        ]);

    }

    public static function findReturnDay($rentedCar) :string
    {
        // search in extended_rents see is there a record with car_id and statistics_id if has give all and take last
        $extendedRents = ExtendRent::where("car_id", $rentedCar->car_id)
            ->where("statistics_id", $rentedCar->inStatistics->id)
            ->orderBy("created_at", "asc")->get();
        if($extendedRents->isEmpty()){
            // first time extend the rent
            return Carbon::createFromFormat('d/m/Y', $rentedCar->return_date)
                ->addDay()
                ->format('Y-m-d');
        }
        // already extended rent
        return Carbon::createFromFormat('d/m/Y', $extendedRents->last()->return_date)
            ->addDay()
            ->format('Y-m-d');
    }
}
