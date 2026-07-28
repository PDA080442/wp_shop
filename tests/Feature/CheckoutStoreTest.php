<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_checkout_creates_order_and_clears_cart(): void
    {
        $product = Product::factory()->create([
            'name' => 'Order Widget',
            'price' => 100.00,
            'stock' => 10,
        ]);
        session(['cart' => [$product->id => 2]]);

        $response = $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Иван Петров',
                'customer_email' => 'ivan@example.com',
            ]);

        $order = Order::query()->first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order));
        $response->assertSessionHas('success', 'Заказ успешно оформлен.');
        $response->assertSessionHas('last_order_id', $order->id);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'Иван Петров',
            'customer_email' => 'ivan@example.com',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'Order Widget',
            'quantity' => 2,
        ]);

        $this->assertSame(8, $product->fresh()->stock);
        $this->assertSame([], session('cart'));
        $this->assertSame(200.0, (float) $order->fresh()->total);
    }

    public function test_checkout_validation_errors_preserve_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        session(['cart' => [$product->id => 1]]);

        $response = $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => '',
                'customer_email' => 'not-an-email',
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHasErrors(['customer_name', 'customer_email']);
        $this->assertSame(0, Order::query()->count());
        $this->assertEquals([$product->id => 1], session('cart'));
    }

    public function test_checkout_redirects_when_cart_is_empty(): void
    {
        $response = $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Иван Петров',
                'customer_email' => 'ivan@example.com',
            ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertSame(0, Order::query()->count());
    }

    public function test_checkout_fails_when_stock_insufficient_and_preserves_cart(): void
    {
        $product = Product::factory()->create([
            'name' => 'Low Stock Item',
            'stock' => 2,
        ]);
        session(['cart' => [$product->id => 5]]);

        $response = $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Иван Петров',
                'customer_email' => 'ivan@example.com',
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHasErrors(['checkout']);
        $this->assertSame(0, Order::query()->count());
        $this->assertEquals([$product->id => 5], session('cart'));
        $this->assertSame(2, $product->fresh()->stock);
    }
}
