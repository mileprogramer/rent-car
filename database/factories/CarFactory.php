<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'license' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'model' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'brand' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'year' => $this->faker->word(),
            'price_per_day' => $this->faker->word(),
            'transmission_type' => $this->faker->randomElement(["automatic","manuel","semi-automatic",""]),
            'number_of_doors' => $this->faker->word(),
            'person_fit_in' => $this->faker->word(),
            'car_consumption' => $this->faker->word(),
            'air_conditioning_type' => $this->faker->randomElement(["Manual","Automatic","DualZone","MultiZone","RearSeat","Electric","Hybrid","SolarPowered"]),
            'status' => $this->faker->randomElement(["available","rented","sold","broken",""]),
        ];
    }
}
