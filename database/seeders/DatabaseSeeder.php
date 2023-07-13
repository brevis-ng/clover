<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a admin user
        User::create([
            "name" => "Brevis Nguyen",
            "email" => "brevisnguyen@gmail.com",
            "password" => Hash::make("admin123"),
        ]);

        Category::factory()
            ->has(
                Product::factory()
                    ->hasAttached(Order::factory()->count(3), [
                        "quantity" => fake()->randomDigitNot(0),
                        "amount" => fake()->randomFloat(2, 500, 5000),
                    ])
                    ->count(10)
            )
            ->count(10)
            ->create();
    }
}
