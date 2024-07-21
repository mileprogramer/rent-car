<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Collection
    {
        return Car::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Show the cars that are sold.
     */
    public function sold()
    {
        return Car::where('status', 'sold')->get();
    }
}
