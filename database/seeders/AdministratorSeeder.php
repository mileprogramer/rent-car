<?php

namespace Database\Seeders;

use App\Models\Administrator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Administrator::create([
            'first_name' => 'Nemanja',
            'last_name' => 'Milic',
            'username' => 'adminMile',
            'email' => 'nemanjamilic489@gmail.com',
            'password' => bcrypt('nemanja'),
        ]);
    }
}
