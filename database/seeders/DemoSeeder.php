<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::query()->exists()) {
            $this->command?->warn('Orders already exist. Skip DemoSeeder.');

            return;
        }

        Order::factory()->count(10)->create();

        $this->command?->info('Created 10 demo orders with items.');
    }
}
