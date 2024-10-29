<?php

namespace App\Http\Controllers;

use App\Handlers\RentedCarHandler;
use App\Models\Car;
use App\Models\ExtendRent;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Models\User;
use App\Repository\StatisticsCarsRepository;
use App\Service\CarService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Repository\RentedCarRepository;

class RentedCarController extends Controller
{

    public function __construct()
    {
        $this->rentedCarHandler = new RentedCarHandler();
    }

    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        return response()->json(
            $this->rentedCarHandler->getCars()
        );
    }

    /**
     * Search for cars.
     */
    public function search(Request $request) : JsonResponse
    {
        return response()->json($this->rentedCarHandler->search($request->query("term")), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->rentedCarHandler->store($request);

        return response()->json([
            'message'=> $result['message']
        ], $result['statusCode']);
    }

    /**
     * When user returns car or rent is done
     */
    public function return(Request $request)
    {
        $result = $this->rentedCarHandler->returnCar($request);

        return response()->json([
            "message" => $result['message']
        ], $result['statusCode']);
    }

    /**
     * Count rented cars per month or in total
     */
    public function total(Request $request)
    {
        $results = $this->rentedCarHandler->countTotalCars($request);
        return response()->json([
            "total_cars" => $results['totalCars']
        ]);
    }

    /**
     * Latest rented cars
     */
    public function latest()
    {
        return response()->json($this->rentedCarHandler->latestRented());
    }

}
