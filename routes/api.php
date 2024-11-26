<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::post("admin/login", [\App\Http\Controllers\AuthController::class, "login"]);
Route::post("admin/logout", [\App\Http\Controllers\AuthController::class, "logout"]);

Route::middleware('auth:sanctum')->get('/admin/logged', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(UserController::class)->group(function (){
        Route::get('users', 'index');
        Route::get('users/search', 'search');

        // Users post
        Route::post('user/edit', 'update');
    });

    Route::controller(CarController::class)->group(function(){
        // Cars get
        Route::get('cars', 'index');
        Route::get('cars/search', 'search');
        Route::get('cars/available', 'available');
        Route::get('cars/available/search', 'searchAvailable');
        Route::get('cars/available/total', 'total');

        // Cars post
        Route::post('/car/add', 'store');
        Route::post('/car/update/', 'update');
    });

    // Deleted cars routes
    Route::get('cars/deleted', [DeleteCarController::class, 'index']);
    Route::post('/car/delete', [DeleteCarController::class, 'delete']);

    // Rented cars get
    Route::controller(RentedCarController::class)->group(function(){
        Route::get('cars/rented', 'index');
        Route::get('cars/rented/search', 'search');
        Route::get('cars/rented/total', 'total');
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
        Route::get('cars/best-selling', 'bestSelling');
    });



    Route::post('/car/rent/extend', [ExtenedRentController::class, 'store']);
});


