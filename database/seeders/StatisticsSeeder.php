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
        $users = User::all();

        // Generate sample statistics records
        $statistics = [];

        for ($i = 0; $i < $cars->count(); $i++) {
            $user = $users->random();

            // Create a statistics record
            $statistics[] = [
                'user_id' => $user->id,
                'car_id' => $cars[$i]->car_id,
                'start_date' => now()->subDays(rand(1, 15))->toDateString(),
                'wanted_return_date' => now()->addDays(rand(16, 30))->toDateString(),
                'price_per_day' => $cars[$i]->price_per_day,
                'discount' => rand(0, 20),
                'reason_for_discount' => $this->getRandomDiscountReason(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Statistics::insert($statistics);
    }

    // Function to get a random reason for discount
    private function getRandomDiscountReason()
    {
        $reasons = [
            'Loyalty discount',
            'Seasonal discount',
            'Promotional discount',
            'Referral discount',
            'Holiday special',
        ];

        return $reasons[array_rand($reasons)];
    }
}
