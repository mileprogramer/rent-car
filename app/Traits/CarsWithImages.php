<?php

namespace App\Traits;

use App\Models\Car;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Service\CarService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait CarsWithImages
{

    static function getCarsWithImages(
        Builder|Statistics|Car|RentedCar $query,
        bool $paginate = true,
        int $perPage = 10,
    ) : Collection|LengthAwarePaginator
    {

        $cars = $paginate ? $query->paginate($perPage) : $query->get();

//        return $cars->transform(function ($record) {
//            if ($record->car) {
//                $record->car->images = CarService::getImagesForCar($record->car);
//            }
//            return $record;
//        });

        $cars->setCollection(
            $cars->getCollection()->map(function ($record) {
                // for the Statistics, RentedCar and etc...
                if ($record->car) {
                    $record->car->images = CarService::getImagesForCar($record->car);
                }
                // for Car model
                if($record instanceof Car)
                {
                    $record->images = CarService::getImagesForCar($record);
                }
                return $record;
            })
        );

        return $cars;
    }

}
