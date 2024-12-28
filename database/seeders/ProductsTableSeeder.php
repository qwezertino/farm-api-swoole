<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 50 unique products
        foreach (range(1, 50) as $index) {
            DB::table('products')->insert([
                'name' => $faker->unique()->word,
                'price' => $faker->randomFloat(2, 5, 100),  // Random price between 5 and 100
                'amount' => $faker->numberBetween(1, 100),  // Random amount between 1 and 100
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
