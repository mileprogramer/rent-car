<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;
use App\Models\ExtendRents;
use App\Models\User;

class ExtendRentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExtendRents::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->word(),
            'id_car' => $this->faker->word(),
            'start_date' => $this->faker->word(),
            'return_date' => $this->faker->word(),
            'extend_start_date' => $this->faker->word(),
            'price_per_day' => $this->faker->word(),
            'discount' => $this->faker->word(),
            'reason_for_discount' => $this->faker->word(),
            'car_id' => Car::factory(),
            'user_id' => User::factory(),
        ];
    }
}
