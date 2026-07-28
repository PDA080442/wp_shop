<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock');
            $table->timestamps();
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('ALTER TABLE products ADD CHECK (price >= 0)');
            DB::statement('ALTER TABLE products ADD CHECK (stock >= 0)');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE products ADD CONSTRAINT products_price_check CHECK (price >= 0)');
            DB::statement('ALTER TABLE products ADD CONSTRAINT products_stock_check CHECK (stock >= 0)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
