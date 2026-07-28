<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'total' => 0,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Order $order) {
            OrderItem::factory()
                ->count(fake()->numberBetween(1, 5))
                ->create(['order_id' => $order->id]);
        });
    }
}
