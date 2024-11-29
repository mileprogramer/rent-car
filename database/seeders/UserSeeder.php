<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Core\Number;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Nemanja Milic',
                'email' => 'adminMile@rentcar.com',
                'username' => 'adminMile',
                'email_verified_at' => now(),
                'phone' => "+1 (212) 551-2376",
                'card_id' => '654-32-1487',
                'password' => Hash::make('adminMile'),
                'remember_token' => Str::random(10),
                'role' => "super_admin",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Name and Last name',
                'email' => 'name.lastname@rentcar.com',
                'username' => "admin@admin",
                'email_verified_at' => now(),
                'phone' => "+1 (212) 535-2376",
                'card_id' => '654-41-1987',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => "admin",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'username' => "no-username",
                'email_verified_at' => now(),
                'phone' => "+1 (212) 555-2376",
                'card_id' => '654-32-1987',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => "user",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'username' => "no-username",
                'email_verified_at' => now(),
                "phone" => "+1 (310) 555-4819",
                'card_id' => '321-76-5432',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => "user",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Johnson',
                'username' => "no-username",
                'email' => 'alice.johnson@example.com',
                'email_verified_at' => now(),
                "phone" => '+1 (305) 555-7284',
                'card_id' => '567-89-0123' ,
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => "user",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'bob.brown@example.com',
                'username' => "no-username",
                'email_verified_at' => now(),
                'phone' => '+1 (312) 555-1963',
                'card_id' => '123-45-6789',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => "user",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Charlie Davis',
                'email' => 'charlie.davis@example.com',
                'username' => "no-username",
                'email_verified_at' => now(),
                'phone' => '+1 (415) 555-9823',
                'card_id' => '123-75-5789',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => "user",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);
    }
}
