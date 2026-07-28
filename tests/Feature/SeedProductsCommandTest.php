<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeedProductsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_seed_creates_default_count(): void
    {
        $this->artisan('products:seed')
            ->assertSuccessful();

        $this->assertSame(50, Product::query()->count());
    }

    public function test_products_seed_respects_count_argument(): void
    {
        $this->artisan('products:seed', ['count' => 10])
            ->assertSuccessful();

        $this->assertSame(10, Product::query()->count());
    }

    public function test_products_seed_fresh_truncates_then_creates(): void
    {
        Product::factory()->count(5)->create();

        $this->artisan('products:seed', [
            'count' => 7,
            '--fresh' => true,
        ])->assertSuccessful();

        $this->assertSame(7, Product::query()->count());
    }

    public function test_products_seed_outputs_created_message(): void
    {
        $this->artisan('products:seed', ['count' => 3])
            ->expectsOutputToContain('Created 3 products')
            ->assertSuccessful();
    }
}
