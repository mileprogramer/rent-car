<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\RentedCar;
use App\Models\Statistics;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = RentedCar::all();

        // Generate sample statistics records
        $statistics = [];

        for ($i = 0; $i < $cars->count(); $i++) {
            // Create a statistics record
            $statistics[] = [
                'user_id' => $cars[$i]->user_id,
                'car_id' => $cars[$i]->car_id,
                'start_date' => $cars[$i]->start_date_default_format,
                'wanted_return_date' => $cars[$i]->return_date_default_format,
                'price_per_day' => $cars[$i]->price_per_day,
                'discount' => $cars[$i]->discount,
                'reason_for_discount' => $cars[$i]->reasonForDiscount,
                'created_at' => $cars[$i]->created_at,
                'updated_at' => $cars[$i]->updated_at,
            ];
        }

        Statistics::insert($statistics);
    }
}
