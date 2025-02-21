<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Luke Woods',
                'email' => 'woodsluke23@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt('Happy1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            [
                'name' => 'John Christiansen',
                'email' => 'john@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt('Funnybunny1990'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]

        ];
        User::insert($users);
    }
}
