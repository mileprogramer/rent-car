<?php

namespace App\Service;

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

    static function calculateTotalPrice(Statistics|Builder $statistic, $initialReturnDate) :int
    {
        if(!$statistic->extend_rent){
            $startDate = Carbon::createFromFormat('d/m/Y', trim($statistic->start_date));
            $totalPrice = $startDate->diffInDays(now()) * ($statistic->price_per_day - ($statistic->discount / 100) * $statistic->price_per_day);
            return $totalPrice;
        }

        if($statistic->extend_rent)
        {
            $initialStartDate = Carbon::createFromFormat('d/m/Y', trim($statistic->start_date));
            $firstValue = $initialStartDate->diffInDays(now()) * ($statistic->price_per_day - ($statistic->discount / 100) * $statistic->price_per_day);
            $totalPrice = 0;
            $extendedRents = $statistic->extendedRents;

            $updatedExtendedRents = $extendedRents;

            for ($i = $updatedExtendedRents->count() - 1; $i >= 0; $i--) {
                $rent = $updatedExtendedRents[$i];

                $returnDate = Carbon::createFromFormat('d/m/Y', $rent['return_date']);
                $startDate = Carbon::createFromFormat('d/m/Y', $rent['start_date']);
                $today = Carbon::now();

                if ($returnDate->isAfter($today)) {
                    $updatedExtendedRents[$i]['return_date'] = $today->format('d/m/Y');
                }

                if ($i === $updatedExtendedRents->count() - 1 && $startDate->isAfter($today)) {
                    $updatedExtendedRents->pop();
                }
            }

            foreach ($extendedRents as $extendedRent) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->start_date));
                $returnDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->return_date));

                $totalDays = $returnDate->diffInDays($startDate);
                $totalPriceForRent = $extendedRent->price_per_day * $totalDays;
                $discountedPrice = $totalPriceForRent - ($totalPriceForRent * ($extendedRent->discount / 100));
                $totalPrice += $discountedPrice;
            }

            return $totalPrice + $firstValue;

        }
        return 0;
    }

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
