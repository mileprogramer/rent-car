<?php

namespace App\Services;

use App\Http\Requests\CarImagesRequest;
use App\Models\Car;
use App\Models\RentedCar;
use App\Models\Statistics;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CarService
{



    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    function storeImagesForCar(Car $car, array $images) :void
    {
        foreach ($images as $image) {
            $car->addMedia($image)->toMediaCollection('cars_images');
        }
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    function updateImagesForCar(Car $car, array $newImages) : void
    {
        $car->clearMediaCollection('cars_images');

        foreach ($newImages as $image) {
            $car->addMedia($image)->toMediaCollection('cars_images');
        }
    }

    function getImagesForCar(Car $car) :Collection
    {
        if ($car?->media) {
            return $car->images = $car->media->map(function ($media) {
                return $media->getUrl();
            });
        }

        return collect([]);
    }

    function getCarsWithImages(
        Builder|Statistics|Car|RentedCar $query,
        bool $paginate = true,
        int $perPage = 10,
    ) : Collection|LengthAwarePaginator
    {

        $cars = $paginate ? $query->paginate($perPage) : $query->get();

        $cars->setCollection(
            $cars->getCollection()->map(function ($record) {
                // for the Statistics, RentedCar and etc records...
                if ($record->car) {
                    $record->car->images = $this->getImagesForCar($record->car);
                }
                else
                {
                    // for Car records
                    $record->images = $this->getImagesForCar($record);
                }
                return $record;
            })
        );

        return $cars;
    }

}
