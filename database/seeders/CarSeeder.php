<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Model X',
                'brand' => 'Tesla',
                'year' => 2021,
                'price_per_day' => 150.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 15.5,
                'air_conditioning_type' => 'dual zone',
                'status' => 'available',
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Civic',
                'brand' => 'Honda',
                'year' => 2019,
                'price_per_day' => 60.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.5,
                'air_conditioning_type' => 'manual',
                'status' => 'rented',
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Camry',
                'brand' => 'Toyota',
                'year' => 2020,
                'price_per_day' => 80.00,
                'transmission_type' => 'semi-automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 13.2,
                'air_conditioning_type' => 'automatic',
                'status' => 'available',
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Mustang',
                'brand' => 'Ford',
                'year' => 2018,
                'price_per_day' => 120.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 2,
                'person_fit_in' => 4,
                'car_consumption' => 10.5,
                'air_conditioning_type' => 'electric',
                'status' => 'deleted',
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Accord',
                'brand' => 'Honda',
                'year' => 2017,
                'price_per_day' => 70.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 11.7,
                'air_conditioning_type' => 'rear seat',
                'status' => 'broken',
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'A4',
                'brand' => 'Audi',
                'year' => 2021,
                'price_per_day' => 130.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 14.5,
                'air_conditioning_type' => 'multi zone',
                'status' => 'available',
            ],
        ];

        Car::insert($cars);

    }
}
