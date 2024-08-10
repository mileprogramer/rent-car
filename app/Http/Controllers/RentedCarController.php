<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\RentedCar;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Collection;

class RentedCarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(RentedCar::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(RentedCar::rules($request->all()));
        RentedCar::create($data);
        // rentedCar observer is called to update the car status
        return response()->json(['message'=> 'Successfully rented car'], 201);
    }

    /**
     * Extend the rent
     */
    public function extend(string $id)
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
