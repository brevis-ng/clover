<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "customer_id" => Customer::factory(),
            "status" => fake()->randomElement(OrderStatus::class),
            "address" => fake()->address(),
            "total_amount" => fake()->randomFloat(2, 1000, 10000),
            "shipping_amount" => fake()->randomFloat(2, 50, 200),
            "payment_method" => fake()->randomElement(["cod", "bank"]),
            "notes" => fake()->sentence(),
        ];
    }
}
