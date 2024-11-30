<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentedCarController;
use App\Http\Controllers\DeleteCarController;
use App\Http\Controllers\ExtendRentController;
use App\Http\Controllers\StatisticController;


Route::post("admin/login", [\App\Http\Controllers\AuthController::class, "login"]);
Route::post("admin/logout", [\App\Http\Controllers\AuthController::class, "logout"]);

Route::middleware('auth:sanctum')->get('/admin/logged', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(UserController::class)->group(function (){
        Route::get('users', 'index');
        Route::get('users/search', 'search');

        Route::post('user/edit', 'update');
    });

    Route::controller(CarController::class)->group(function(){
        Route::get('cars', 'index');
        Route::get('cars/search', 'search');
        Route::get('cars/available', 'available');
        Route::get('cars/available/search', 'searchAvailable');
        Route::get('cars/available/total', 'total');

        Route::post('/car/add', 'store');
        Route::post('/car/update/', 'update');
    });

    Route::controller(DeleteCarController::class)->group(function(){
        Route::get("cars/deleted", 'index');
        Route::post("car/delete", 'delete')->middleware("super_admin");
    });

    // Rented cars get
    Route::controller(RentedCarController::class)->group(function(){
        Route::get('cars/rented', 'index');
        Route::get('cars/rented/search', 'search');
        Route::get('cars/rented/total', 'countTotalCars');
        Route::get('cars/rented/latest', 'latest');

        // Rented cars post
        Route::post('/car/rent', 'store');
        Route::post('/car/rent/return', 'return');
    });

    Route::controller(StatisticController::class)->group(function(){
        // Statistics get
        Route::get('cars/statistics', 'index');
        Route::get('cars/returned/total', 'returnedTotal');
        Route::get('cars/returned/latest', 'latest');
        Route::get('cars/statistics/search', 'search');
    });

    Route::post('/car/rent/extend', [ExtendRentController::class, 'store']);
});


