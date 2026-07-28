<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_factory_creates_order_with_items(): void
    {
        $order = Order::factory()->create();

        $order->refresh()->load('items');

        $this->assertGreaterThanOrEqual(1, $order->items->count());
        $this->assertLessThanOrEqual(5, $order->items->count());
        $this->assertGreaterThan(0, (float) $order->total);
        $this->assertEqualsWithDelta($order->calculateTotal(), (float) $order->total, 0.01);
    }

    public function test_order_item_factory_matches_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Factory Match Product',
            'price' => 123.45,
            'stock' => 10,
        ]);

        $item = OrderItem::factory()->create([
            'product_id' => $product->id,
        ]);

        $this->assertSame('Factory Match Product', $item->product_name);
        $this->assertEqualsWithDelta(123.45, (float) $item->price, 0.01);
        $this->assertNotNull($item->order_id);
    }
}
