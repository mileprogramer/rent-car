<?php

namespace App\Handlers;

use App\Enums\CarStatus;
use App\Http\Requests\CarImagesRequest;
use App\Http\Requests\DefaultCarRequest;
use App\Models\Car;
use App\Service\CarService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;

class CarHandler
{

    public static function searchAllCars(string $searchTerm) :LengthAwarePaginator
    {
        return CarService::getCarsWithImages(Car::query()
            ->where(function ($query) use ($searchTerm) {
                $query->where('brand', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('license', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('model', 'LIKE', '%' . $searchTerm . '%');
            }));
    }
    public static function countAvailableCars() :int
    {
        return Car::where("status", Car::status())
            ->count();
    }
}
