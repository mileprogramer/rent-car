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
        $cars = Car::all();
        $users = User::all();

        $rentedCars = [];

        for ($i = 0; $i < 3; $i++) {
            $car = $cars->random();
            $user = $users->random();

            $rentedCars[] = [
                'user_id' => $user->id,
                'car_id' => $car->id,
                'start_date' => now()->subDays(rand(1, 30))->toDateString(),
                'return_date' => now()->addDays(rand(1, 30))->toDateString(),
                'price_per_day' => $car->price_per_day,
                'discount' => 0,
                'reason_for_discount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        RentedCar::insert($rentedCars);
    }
}
