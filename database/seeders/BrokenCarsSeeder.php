<?php

namespace Database\Seeders;

use App\Models\BrokenCar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrokenCarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data  = $this->getData();
        BrokenCar::insert($data);
    }

    protected function getData() :array
    {
        return [
            "car_id" => '5',
            "user_id" => null,
            "start_date_broke" => '2024-12-07',
            "start_date_repair" => '2024-12-09',
            "description" => 'Air conditioner is not working',
            "user_fault" => false,
            "returned_date" => '2024-12-10',
            "cost" => 300,
            'report' => 'Freon was filled'
        ];
    }

}
