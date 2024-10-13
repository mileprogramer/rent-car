<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\RentedCar;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

            // I have to use DB to bypass the mutators
            DB::table('rented_cars')->insert([
                'user_id' => $user->id,
                'car_id' => $car->id,
                'start_date' => now()->subDays(rand(50, 80)),  // directly without mutators
                'return_date' => now()->addDays(rand(10, 15)), // directly without mutators
                'price_per_day' => $car->price_per_day,
                'discount' => 0,
                'reason_for_discount' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $car = Car::where("id", $cars[$i]->id)->firstOrFail();
            $car->status = RentedCar::status();
            $car->save();
            // add delay for different created_at value for rented cars
            sleep(1);
        }

    }
}
