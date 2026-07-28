<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_default_count_products(): void
    {
        $this->seed(ProductSeeder::class);

        $this->assertSame(50, Product::query()->count());
    }

    public function test_seeder_respects_count_parameter(): void
    {
        $this->app->make(ProductSeeder::class)->run(10);

        $this->assertSame(10, Product::query()->count());
    }

    public function test_seeder_is_idempotent_without_fresh(): void
    {
        $this->app->make(ProductSeeder::class)->run();
        $this->app->make(ProductSeeder::class)->run();

        $this->assertSame(50, Product::query()->count());
    }

    public function test_seeder_fresh_replaces_products(): void
    {
        Product::factory()->count(5)->create();

        putenv('PRODUCT_SEED_FRESH=true');
        $_ENV['PRODUCT_SEED_FRESH'] = 'true';
        $_SERVER['PRODUCT_SEED_FRESH'] = 'true';

        try {
            $this->app->make(ProductSeeder::class)->run(20);
        } finally {
            putenv('PRODUCT_SEED_FRESH=false');
            $_ENV['PRODUCT_SEED_FRESH'] = 'false';
            $_SERVER['PRODUCT_SEED_FRESH'] = 'false';
        }

        $this->assertSame(20, Product::query()->count());
    }

    public function test_artisan_db_seed_class_product_seeder_succeeds(): void
    {
        $this->artisan('db:seed', ['--class' => ProductSeeder::class])
            ->assertSuccessful();

        $this->assertSame(50, Product::query()->count());
    }
}
