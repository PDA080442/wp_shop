<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class SeedProductsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'products:seed
                            {count=50 : Number of products to create}
                            {--fresh : Truncate products table before seeding}';

    /**
     * @var string
     */
    protected $description = 'Seed the products table with random products';

    public function handle(): int
    {
        $count = max(0, (int) $this->argument('count'));

        if ($this->option('fresh')) {
            Schema::disableForeignKeyConstraints();
            Product::truncate();
            Schema::enableForeignKeyConstraints();
            $this->warn('Products table truncated.');
        }

        if ($count === 0) {
            $this->info('Created 0 products');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            Product::factory()->create();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Created {$count} products");

        return self::SUCCESS;
    }
}
