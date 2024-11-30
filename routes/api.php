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
        Route::get('users', 'getUsers');
        Route::get('users/search', 'searchUsers');

        Route::post('user/edit', 'updateUser');
    });

    Route::controller(CarController::class)->group(function(){
        Route::get('cars', 'getCars');
        Route::get('cars/search', 'searchCars');
        Route::get('cars/available', 'availableCars');
        Route::get('cars/available/search', 'searchAvailable');
        Route::get('cars/available/total', 'totalAvailableCars');

        Route::post('/car/add', 'createNewCar');
        Route::post('/car/update/', 'updateCar');
    });

    Route::controller(DeleteCarController::class)->group(function(){
        Route::get("cars/deleted", 'getDeletedCars');
        Route::post("car/delete", 'deleteCar')->middleware("super_admin");
    });

    // Rented cars get
    Route::controller(RentedCarController::class)->group(function(){
        Route::get('cars/rented', 'getRentedCars');
        Route::get('cars/rented/search', 'searchRentedCars');
        Route::get('cars/rented/total', 'countTotalCars');
        Route::get('cars/rented/latest', 'latestRentedCars');

        // Rented cars post
        Route::post('/car/rent', 'rentCar');
        Route::post('/car/rent/return', 'returnCar');
    });

    Route::controller(StatisticController::class)->group(function(){
        // Statistics get
        Route::get('cars/statistics', 'getStats');
        Route::get('cars/returned/total', 'returnedTotal');
        Route::get('cars/returned/latest', 'latestReturned');
        Route::get('cars/statistics/search', 'searchStatistics');
    });

    Route::post('/car/rent/extend', [ExtendRentController::class, 'extendRent']);
});


