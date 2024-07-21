<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;
use App\Models\SoldCar;

class SoldCarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SoldCar::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id_car' => $this->faker->word(),
            'date_of_sale' => $this->faker->word(),
            'price' => $this->faker->word(),
            'car_id' => Car::factory(),
        ];
    }
}
