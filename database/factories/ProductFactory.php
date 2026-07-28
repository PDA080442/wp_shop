<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Number of bundled photos in public/images/products.
     */
    protected const IMAGE_COUNT = 12;

    protected static int $imageIndex = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 1, 9999),
            'stock' => fake()->numberBetween(0, 100),
            'image' => static::nextImage(),
        ];
    }

    /**
     * Cycle through the bundled photos so a default seed run has no duplicates.
     */
    protected static function nextImage(): string
    {
        static::$imageIndex = static::$imageIndex % static::IMAGE_COUNT + 1;

        return sprintf('images/products/product-%02d.jpg', static::$imageIndex);
    }

    public function inStock(): static
    {
        return $this->state(fn () => ['stock' => fake()->numberBetween(1, 100)]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }
}
