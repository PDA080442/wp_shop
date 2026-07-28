<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 1, 999),
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (OrderItem $item) {
            $product = $item->product ?? Product::query()->find($item->product_id);

            if ($product) {
                $item->product_name = $product->name;
                $item->price = $product->price;
            }
        });
    }
}
