<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(?int $count = null): void
    {
        $count = $count ?? (int) env('PRODUCT_SEED_COUNT', 12);

        if ($this->shouldFresh()) {
            Product::query()->delete();
            $this->command?->info('Products table cleared.');
        } elseif (Product::query()->exists()) {
            $this->command?->warn('Products already exist. Set PRODUCT_SEED_FRESH=true to reseed.');

            return;
        }

        // Keep a couple of sold-out products so the "out of stock" UI is always demonstrable.
        $outOfStock = $count >= 4 ? 2 : 0;

        Product::factory()->count($count - $outOfStock)->inStock()->create();

        if ($outOfStock > 0) {
            Product::factory()->count($outOfStock)->outOfStock()->create();
        }

        $this->command?->info("Seeded {$count} products ({$outOfStock} out of stock).");
    }

    private function shouldFresh(): bool
    {
        return filter_var(env('PRODUCT_SEED_FRESH', false), FILTER_VALIDATE_BOOL);
    }
}
