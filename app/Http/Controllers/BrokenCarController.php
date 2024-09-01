<?php

namespace App\Http\Controllers;

use App\Models\BrokenCar;
use Illuminate\Http\Request;

class BrokenCarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = BrokenCar::paginate();

        return response()->json($cars);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $carData = $request->validate(BrokenCar::rules_start($request->all()));
        BrokenCar::create($carData);

        return response()->json(['message'=> 'Car is successfully added that is broken']);
    }

    /**
     * Store a date for the going to service.
     */
    public function goToService(Request $request)
    {
        $car = BrokenCar::where('car_id', $request->input('car_id'))->firstOrFail();
        $carData = $request->validate(BrokenCar::rules_medium($car->toArray()));
        $car->update(['start_date_repair'=> $carData['start_date_repair']]);

        return response()->json(['message'=> 'Car status was successfully changed to the at service']);
    }

    /**
     * Store the date when car is fixed
     */
    public function fixed(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
