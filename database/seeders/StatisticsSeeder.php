<?php

namespace Database\Seeders;

use App\Models\Car;
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
        $cars = Car::all();
        $users = User::all();

        // Generate sample statistics records
        $statistics = [];

        for ($i = 0; $i < 3; $i++) {
            // Randomly select a car and user
            $car = $cars->random();
            $user = $users->random();

            // Create a statistics record
            $statistics[] = [
                'user_id' => $user->id,
                'car_id' => $car->id,
                'start_date' => now()->subDays(rand(1, 30))->toDateString(),
                'return_date' => now()->addDays(rand(1, 30))->toDateString(),
                'price_per_day' => $car->price_per_day, // Use the car's price_per_day
                'discount' => rand(0, 20), // Random discount between 0 and 20
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
