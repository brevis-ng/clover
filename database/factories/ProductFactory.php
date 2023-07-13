<?php

namespace Database\Factories;

use App\Enums\Units;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "code" => fake()->regexify("[A-Z]{2}[0-9]{3}"),
            "price" => fake()->randomFloat(2, 50, 1000),
            "old_price" => fake()->randomFloat(2, 50, 1000),
            "cost" => fake()->randomFloat(2, 50, 1000),
            "unit" => fake()->randomElement(Units::class),
            "image" => basename(fake()->image(storage_path("app/products"), category: "food")),
            "description" => fake()->sentence(),
            "is_visible" => fake()->boolean(),
            "remarks" => fake()->word()
        ];
    }
}
