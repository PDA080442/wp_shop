<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(?int $count = null): void
    {
        $count = $count ?? (int) env('PRODUCT_SEED_COUNT', 50);

        if ($this->shouldFresh()) {
            Product::query()->delete();
            $this->command?->info('Products table cleared.');
        } elseif (Product::query()->exists()) {
            $this->command?->warn('Products already exist. Set PRODUCT_SEED_FRESH=true to reseed.');

            return;
        }

        Product::factory()->count($count)->create();

        $this->command?->info("Seeded {$count} products.");
    }

    private function shouldFresh(): bool
    {
        return filter_var(env('PRODUCT_SEED_FRESH', false), FILTER_VALIDATE_BOOL);
    }
}
