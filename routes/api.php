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

// Users get
Route::get('users', [UserController::class, 'index']);
Route::get('users/search', [UserController::class, 'search']);

// Users post
Route::post('user/edit', [UserController::class, 'update']);

// Cars get
Route::get('cars', [CarController::class, 'index']);
Route::get('cars/filter', [CarController::class, 'filter']);
Route::get('cars/available', [CarController::class, 'available']);
Route::get('cars/available/total', [CarController::class, 'total']);

// Cars post
Route::get('/car/edit/{car}', [CarController::class, 'edit']);
Route::get('/car/show/{car}', [CarController::class, 'edit']);
Route::post('/car/add', [CarController::class, 'store']);
Route::post('/car/update/', [CarController::class, 'update']);

// Deleted cars routes
Route::get('cars/deleted', [DeleteCarController::class, 'index']);
Route::post('/car/delete', [DeleteCarController::class, 'delete']);

// Rented cars get
Route::get('cars/rented', [RentedCarController::class, 'index']);
Route::get('cars/rented/search', [RentedCarController::class, 'search']);
Route::get('cars/rented/total', [RentedCarController::class, 'total']);
Route::get('cars/rented/latest', [RentedCarController::class, 'latest']);

// Rented cars post
Route::post('/car/rent', [RentedCarController::class, 'store']);
Route::post('/car/rent/return', [RentedCarController::class, 'return']);

// Statistics get
Route::get('cars/statistics', [StatisticController::class, 'index']);
Route::get('cars/returned/total', [StatisticController::class, 'returnedTotal']);
Route::get('cars/returned/latest', [StatisticController::class, 'latest']);
Route::get('cars/statistics/search', [StatisticController::class, 'search']);
Route::get('cars/best-selling', [StatisticController::class, 'bestSelling']);

Route::post('/car/rent/extend', [ExtenedRentController::class, 'store']);

// Broken routes to de done
Route::post('/car/broke', [BrokenCarController::class, 'store']);
Route::post('/car/broke/service', [BrokenCarController::class, 'goToService']);
Route::post('/car/broke/fixed', [BrokenCarController::class, 'fixed']);
