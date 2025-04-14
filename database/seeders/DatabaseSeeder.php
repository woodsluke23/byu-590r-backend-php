<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, seed sauces and chicken types
        $this->call([
            SaucesSeeder::class,
            ChickenTypesSeeder::class,
        ]);

        // Seed the user
        $this->call(UserSeeder::class);

        // Then, seed the restaurants
        $this->call(RestaurantsSeeder::class);
    }
}
