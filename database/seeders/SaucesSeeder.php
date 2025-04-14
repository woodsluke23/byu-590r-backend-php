<?php

namespace Database\Seeders;

use App\Models\Sauce;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SaucesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sauces = [
            [
                'sauce_name' => "Zax Sauce",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'sauce_name' => "Dave's Sauce",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'sauce_name' => "SUPER CHIX sauce",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'sauce_name' => "Atomic",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'sauce_name' => "Blazin",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

    Sauce::insert($sauces);
    }
}
