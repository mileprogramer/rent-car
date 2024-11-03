<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\DeleteCar;
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
                'status' => Car::status(),
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
                'status' => Car::status(),
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
                'status' => Car::status(),
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
                'status' => DeleteCar::status(),
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
                'status' => DeleteCar::status(),
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
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => '3 Series',
                'brand' => 'BMW',
                'year' => 2022,
                'price_per_day' => 140.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 16.0,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'C-Class',
                'brand' => 'Mercedes-Benz',
                'year' => 2020,
                'price_per_day' => 135.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 15.0,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Altima',
                'brand' => 'Nissan',
                'year' => 2019,
                'price_per_day' => 70.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 13.5,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => '3 Series',
                'brand' => 'BMW',
                'year' => 2020,
                'price_per_day' => 110.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.8,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Corolla',
                'brand' => 'Toyota',
                'year' => 2018,
                'price_per_day' => 65.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 11.2,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Charger',
                'brand' => 'Dodge',
                'year' => 2019,
                'price_per_day' => 125.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 13.5,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Fusion',
                'brand' => 'Ford',
                'year' => 2021,
                'price_per_day' => 90.00,
                'transmission_type' => 'semi-automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 10.5,
                'air_conditioning_type' => 'multi zone',
                'status' => DeleteCar::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'CX-5',
                'brand' => 'Mazda',
                'year' => 2019,
                'price_per_day' => 85.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 11.9,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'X5',
                'brand' => 'BMW',
                'year' => 2021,
                'price_per_day' => 140.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 5,
                'car_consumption' => 13.7,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Sentra',
                'brand' => 'Nissan',
                'year' => 2018,
                'price_per_day' => 60.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 10.8,
                'air_conditioning_type' => 'manual',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Impreza',
                'brand' => 'Subaru',
                'year' => 2020,
                'price_per_day' => 95.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.3,
                'air_conditioning_type' => 'automatic',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Corolla',
                'brand' => 'Toyota',
                'year' => 2018,
                'price_per_day' => 55.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 10.8,
                'air_conditioning_type' => 'manual',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'X5',
                'brand' => 'BMW',
                'year' => 2021,
                'price_per_day' => 200.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 18.5,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Charger',
                'brand' => 'Dodge',
                'year' => 2017,
                'price_per_day' => 90.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 14.2,
                'air_conditioning_type' => 'automatic',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Q7',
                'brand' => 'Audi',
                'year' => 2022,
                'price_per_day' => 180.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 7,
                'car_consumption' => 19.5,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Golf',
                'brand' => 'Volkswagen',
                'year' => 2019,
                'price_per_day' => 65.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.0,
                'air_conditioning_type' => 'manual',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Cherokee',
                'brand' => 'Jeep',
                'year' => 2020,
                'price_per_day' => 140.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 16.0,
                'air_conditioning_type' => 'rear seat',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Altima',
                'brand' => 'Nissan',
                'year' => 2018,
                'price_per_day' => 75.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 13.5,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'S60',
                'brand' => 'Volvo',
                'year' => 2021,
                'price_per_day' => 160.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 17.0,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => '3 Series',
                'brand' => 'BMW',
                'year' => 2020,
                'price_per_day' => 110.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 15.0,
                'air_conditioning_type' => 'automatic',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Fusion',
                'brand' => 'Ford',
                'year' => 2018,
                'price_per_day' => 85.00,
                'transmission_type' => 'semi-automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 13.8,
                'air_conditioning_type' => 'electric',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'S-Class',
                'brand' => 'Mercedes-Benz',
                'year' => 2022,
                'price_per_day' => 220.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 19.0,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Tucson',
                'brand' => 'Hyundai',
                'year' => 2020,
                'price_per_day' => 75.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.5,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Explorer',
                'brand' => 'Ford',
                'year' => 2019,
                'price_per_day' => 130.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 7,
                'car_consumption' => 14.5,
                'air_conditioning_type' => 'rear seat',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Optima',
                'brand' => 'Kia',
                'year' => 2018,
                'price_per_day' => 70.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 11.8,
                'air_conditioning_type' => 'manual',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Outback',
                'brand' => 'Subaru',
                'year' => 2020,
                'price_per_day' => 85.00,
                'transmission_type' => 'semi-automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 5,
                'car_consumption' => 13.3,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'XC90',
                'brand' => 'Volvo',
                'year' => 2022,
                'price_per_day' => 190.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 7,
                'car_consumption' => 17.0,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Q5',
                'brand' => 'Audi',
                'year' => 2021,
                'price_per_day' => 150.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 14.5,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'A8',
                'brand' => 'Audi',
                'year' => 2022,
                'price_per_day' => 170.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 17.2,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Escalade',
                'brand' => 'Cadillac',
                'year' => 2021,
                'price_per_day' => 220.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 7,
                'car_consumption' => 14.8,
                'air_conditioning_type' => 'rear seat',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'XC90',
                'brand' => 'Volvo',
                'year' => 2020,
                'price_per_day' => 190.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 7,
                'car_consumption' => 16.0,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Tiguan',
                'brand' => 'Volkswagen',
                'year' => 2019,
                'price_per_day' => 100.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.5,
                'air_conditioning_type' => 'dual zone',
                'status' => DeleteCar::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Explorer',
                'brand' => 'Ford',
                'year' => 2018,
                'price_per_day' => 130.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 7,
                'car_consumption' => 14.5,
                'air_conditioning_type' => 'rear seat',
                'status' => DeleteCar::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Optima',
                'brand' => 'Kia',
                'year' => 2020,
                'price_per_day' => 85.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 11.8,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'CX-9',
                'brand' => 'Mazda',
                'year' => 2021,
                'price_per_day' => 155.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 7,
                'car_consumption' => 15.5,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Patriot',
                'brand' => 'Jeep',
                'year' => 2017,
                'price_per_day' => 80.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.3,
                'air_conditioning_type' => 'manual',
                'status' => DeleteCar::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Kona',
                'brand' => 'Hyundai',
                'year' => 2019,
                'price_per_day' => 95.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 13.0,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Legacy',
                'brand' => 'Subaru',
                'year' => 2022,
                'price_per_day' => 105.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.7,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Mustang',
                'brand' => 'Ford',
                'year' => 2023,
                'price_per_day' => 200.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 2,
                'person_fit_in' => 4,
                'car_consumption' => 15.0,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Camry',
                'brand' => 'Toyota',
                'year' => 2020,
                'price_per_day' => 110.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 13.5,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Civic',
                'brand' => 'Honda',
                'year' => 2019,
                'price_per_day' => 90.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 11.2,
                'air_conditioning_type' => 'manual',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'S-Class',
                'brand' => 'Mercedes-Benz',
                'year' => 2021,
                'price_per_day' => 250.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 16.8,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Q7',
                'brand' => 'Audi',
                'year' => 2022,
                'price_per_day' => 180.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 7,
                'car_consumption' => 14.9,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Corolla',
                'brand' => 'Toyota',
                'year' => 2018,
                'price_per_day' => 85.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 10.5,
                'air_conditioning_type' => 'manual',
                'status' => DeleteCar::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'RAV4',
                'brand' => 'Toyota',
                'year' => 2021,
                'price_per_day' => 135.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 5,
                'car_consumption' => 12.8,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'X5',
                'brand' => 'BMW',
                'year' => 2020,
                'price_per_day' => 190.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 7,
                'car_consumption' => 15.3,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Cherokee',
                'brand' => 'Jeep',
                'year' => 2017,
                'price_per_day' => 140.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 5,
                'person_fit_in' => 5,
                'car_consumption' => 13.2,
                'air_conditioning_type' => 'rear seat',
                'status' => DeleteCar::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Altima',
                'brand' => 'Nissan',
                'year' => 2022,
                'price_per_day' => 120.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 11.7,
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Model 3',
                'brand' => 'Tesla',
                'year' => 2021,
                'price_per_day' => 180.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 0, // Electric
                'air_conditioning_type' => 'dual zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Cayenne',
                'brand' => 'Porsche',
                'year' => 2022,
                'price_per_day' => 300.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 15.0,
                'air_conditioning_type' => 'multi zone',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'Accord',
                'brand' => 'Honda',
                'year' => 2020,
                'price_per_day' => 100.00,
                'transmission_type' => 'manual',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 12.0,
                'air_conditioning_type' => 'manual',
                'status' => Car::status(),
            ],
            [
                'license' => strtoupper(Str::random(7)),
                'model' => 'F-150',
                'brand' => 'Ford',
                'year' => 2021,
                'price_per_day' => 150.00,
                'transmission_type' => 'automatic',
                'number_of_doors' => 4,
                'person_fit_in' => 5,
                'car_consumption' => 18.0,
                'air_conditioning_type' => 'manual',
                'status' => Car::status(),
            ],
        ];
        Car::insert($cars);
        $this->addImages(Car::all());

    }

    protected function addImages($cars){
        foreach ($cars as &$car)
        {
            $car->copyMedia(public_path("car-default.jpg"))->toMediaCollection("cars_images");
        }
    }
}
