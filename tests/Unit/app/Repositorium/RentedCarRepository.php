<?php

namespace app\Repositorium;

use App\Models\RentedCar;

class RentedCarRepository
{
    static function search(string $searchTerm)
    {
        $data = \App\Models\RentedCar::join('cars', 'rented_cars.car_id', '=', 'cars.id')
            ->join('users', 'rented_cars.user_id', '=', 'users.id')
            ->where(function ($query) use ($searchTerm) {
                $query->where('cars.license', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('users.name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('users.card_id', 'LIKE', '%' . $searchTerm . '%');
            })
            ->orderBy('rented_cars.created_at', 'desc')
            ->select([
                'rented_cars.id',
                'cars.license',
                'cars.id as car_id',
                'users.id as user_id',
                'users.phone',
                'users.card_id',
                'users.name',
                'users.email',
                'rented_cars.return_date',
                'rented_cars.start_date',
                'rented_cars.price_per_day',
                'rented_cars.discount',
                'rented_cars.reason_for_discount',
            ])
            ->paginate(RentedCar::$carsPerPage);

        // Transform the data
        $transformedData = $data->getCollection()->transform(function ($item) {

            return [
                'id' => $item->id,
                'return_date' => $item->return_date,
                'start_date' => $item->start_date,
                'price_per_day' => $item->price_per_day,
                'discount' => $item->discount,
                'reason_for_discount' => $item->reason_for_discount,
                'car' => [
                    'license' => $item->license,
                    'id' => $item->car_id,
                ],
                'user' => [
                    'name' => $item->name,
                    'email' => $item->email,
                    'phone' => $item->phone,
                    'card_id' => $item->card_id,
                ],
                'extended_rents' => $item->extendedRents
            ];
        });

        $data->setCollection($transformedData);
        return $data;
    }
}
