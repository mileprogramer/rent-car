<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentedCarController;
use App\Enums\AirConditionerType;

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


Route::get('/test', function(){
    $res = \App\Enums\TransmissionType::values();
    dd($res);
});

Route::get('cars', [CarController::class, 'index']);
Route::get('cars/filter', [CarController::class, 'filter']);
Route::get('cars/sold', [CarController::class, 'sold']);
Route::get('cars/rented', [RentedCarController::class, 'index']);
Route::get('cars/statistics', [RentedCarController::class, 'index']);
Route::get('cars/broken', [RentedCarController::class, 'index']);
Route::get('/car/edit/{car}', [CarController::class, 'edit']);
Route::get('/car/show/{car}', [CarController::class, 'edit']);

Route::post('/car/add', [CarController::class, 'store']);
Route::post('/car/update/{id}', [CarController::class, 'update']);
Route::post('/car/sell', [CarController::class, 'store']);
Route::post('/car/rent', [CarController::class, 'store']);
Route::post('/car/broke', [CarController::class, 'store']);
Route::post('/car/rent/return', [CarController::class, 'store']);
Route::post('/car/broke/return', [CarController::class, 'store']);

