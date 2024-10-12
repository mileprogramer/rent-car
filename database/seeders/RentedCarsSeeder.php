<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\RentedCar;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentedCarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = Car::where("status", Car::status())->get();
        $users = User::all();

        $rentedCars = [];

        for ($i = 0; $i < 4; $i++) {
            $car = $cars[$i];
            $user = $users[$i];

            $rentedCars[] = [
                'user_id' => $user->id,
                'car_id' => $car->id,
                'start_date' => now()->subDays(rand(50, 80)),
                'return_date' => now()->addDays(rand(10, 15)),
                'price_per_day' => $car->price_per_day,
                'discount' => 0,
                'reason_for_discount' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $car = Car::where("id", $cars[$i]->id)->firstOrFail();
            $car->status = RentedCar::status();
            $car->save();
        }

        RentedCar::insert($rentedCars);
    }
}
