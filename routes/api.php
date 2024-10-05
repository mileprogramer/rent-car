<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentedCarController;
use App\Http\Controllers\DeleteCarController;
use App\Http\Controllers\BrokenCarController;
use App\Http\Controllers\ExtenedRentController;
use App\Http\Controllers\StatisticController;


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


Route::get('users', [UserController::class, 'index']);

Route::get('cars', [CarController::class, 'index']);
Route::get('cars/filter', [CarController::class, 'filter']);
Route::get('cars/available', [CarController::class, 'available']);
Route::get('cars/deleted', [DeleteCarController::class, 'index']);
Route::get('cars/rented', [RentedCarController::class, 'index']);
Route::get('cars/statistics', [StatisticController::class, 'index']);
Route::get('cars/broken', [BrokenCarController::class, 'index']);
Route::get('/car/edit/{car}', [CarController::class, 'edit']);
Route::get('/car/show/{car}', [CarController::class, 'edit']);
Route::get('/car/search', [CarController::class, 'edit']);
Route::get('/car/rent/details/{id}', [RentedCarController::class, 'details']);// add total_price

Route::post('/car/add', [CarController::class, 'store']);
Route::post('/car/update/', [CarController::class, 'update']);
Route::post('/car/delete', [DeleteCarController::class, 'delete']);
Route::post('/car/broke', [BrokenCarController::class, 'store']);
Route::post('/car/broke/service', [BrokenCarController::class, 'goToService']);
Route::post('/car/broke/fixed', [BrokenCarController::class, 'fixed']);
Route::post('/car/rent', [RentedCarController::class, 'store']);
Route::post('/car/rent/extend', [ExtenedRentController::class, 'store']); // to do start_date is ex start_date plus one day
Route::post('/car/rent/return', [RentedCarController::class, 'return']);// add total_price

