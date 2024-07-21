<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentedCarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function(){
    $res = \App\Models\Car::all();
    dd($res->toArray());
});

Route::get('cars', [CarController::class, 'index']);
Route::get('cars/sold', [CarController::class, 'sold']);
Route::get('cars/rented', [RentedCarController::class, 'index']);
Route::get('cars/statistics', [RentedCarController::class, 'index']);
