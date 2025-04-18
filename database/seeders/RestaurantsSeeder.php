<?php

namespace Database\Seeders;

use App\Models\ChickenType;
use App\Models\Restaurant;
use App\Models\Sauce;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RestaurantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = [
            [
                'restaurant_name' => "Zaxbys",
                'restaurant_description' => "A fast-casual chain specializing in chicken fingers and wings.",
                'favorite_meal' => "Boneless wings & things, tounge torch sauce ",
                'file' => "images/zaxbys.svg",
                'sauce_id' => Sauce::where('sauce_name', 'Zax Sauce')->value('id'),
                'chicken_type_id' => ChickenType::where('chicken_type_name', 'Fried Tenders')->value('id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'restaurant_name' => "Dave's Hot Chicken",
                'restaurant_description' => "A Nashville hot chicken spot with spice levels for everyone.",
                'favorite_meal' => "2 slides, one extra hot and the other hot",
                'file' => "images/Dave's_Hot_Chicken_logo.png",
                'sauce_id' => Sauce::where('sauce_name', "Dave's Sauce")->value('id'),
                'chicken_type_id' => ChickenType::where('chicken_type_name', 'Nashville Hot')->value('id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'restaurant_name' => "Super Chix",
                'restaurant_description' => "A premium chicken joint serving hand-breaded chicken and frozen custard.",
                'favorite_meal' => "Nashville hot chicken sandwich with an extra filet and rosemary fries",
                'file' => "images/super-chix.png",
                'sauce_id' => Sauce::where('sauce_name', "SUPER CHIX sauce")->value('id'),
                'chicken_type_id' => ChickenType::where('chicken_type_name', 'Fried Tenders')->value('id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'restaurant_name' => "Wingstop",
                'restaurant_description' => "A chicken wing-focused chain with a variety of bold flavors.",
                'favorite_meal' => "20 boneless wings. 5 hot-honey rub, 5 parmesan garlic, 5 lemon pepper, and 5 original hot",
                'file' => "images/wingstop-logo.png",
                'sauce_id' => Sauce::where('sauce_name', "Atomic")->value('id'),
                'chicken_type_id' => ChickenType::where('chicken_type_name', 'Wings')->value('id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'restaurant_name' => "Buffalo Wild Wings",
                'restaurant_description' => "A sports bar chain known for its wings, beer, and sports atmosphere.",
                'favorite_meal' => "Thursday buy 10 get 10 for free! 5 mild, 5 lemon pepper dry rub, 5 parmesan garlic, and 5 Jammin' Jalapeño",
                'file' => "images/Buffalo-Wild-Wings-Symbol.png",
                'sauce_id' => Sauce::where('sauce_name', "Blazin")->value('id'),
                'chicken_type_id' => ChickenType::where('chicken_type_name', 'Wings')->value('id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Restaurant::insert($restaurants);
    }
}
