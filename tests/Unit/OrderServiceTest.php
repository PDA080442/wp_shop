<?php

namespace Tests\Unit;

use App\Exceptions\InsufficientStockException;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_persists_items_and_decrements_stock(): void
    {
        $product = Product::factory()->create([
            'name' => 'Service Product',
            'price' => 50.00,
            'stock' => 10,
        ]);

        $order = (new OrderService)->createOrder(
            ['customer_name' => 'Test User', 'customer_email' => 'test@example.com'],
            [$product->id => 3],
        );

        $this->assertSame('Test User', $order->customer_name);
        $this->assertCount(1, $order->items);
        $this->assertSame('Service Product', $order->items->first()->product_name);
        $this->assertSame(150.0, (float) $order->total);
        $this->assertSame(7, $product->fresh()->stock);
    }

    public function test_create_order_throws_when_stock_insufficient(): void
    {
        $product = Product::factory()->create(['stock' => 1]);

        $this->expectException(InsufficientStockException::class);

        (new OrderService)->createOrder(
            ['customer_name' => 'Test User', 'customer_email' => 'test@example.com'],
            [$product->id => 5],
        );
    }
}
