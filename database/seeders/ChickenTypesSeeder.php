<?php

namespace Database\Seeders;

use App\Models\ChickenType;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChickenTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chicken_types = [
            [
                'chicken_type_name' => "Wings",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'chicken_type_name' => "Nashville Hot",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'chicken_type_name' => "Fried Tenders",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
    ChickenType::insert($chicken_types);
    }
}
