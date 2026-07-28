<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create([
            'name' => 'Checkout Test Product',
            'price' => 100.00,
            'stock' => 10,
        ]);
    }

    public function test_checkout_redirects_to_cart_when_cart_is_empty(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Сначала добавьте товары в корзину.');
    }

    public function test_checkout_store_redirects_to_cart_when_cart_is_empty(): void
    {
        $response = $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Иван Петров',
                'customer_email' => 'ivan@example.com',
            ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertSame(0, Order::query()->count());
    }

    public function test_checkout_creates_order_and_order_items_in_database(): void
    {
        session(['cart' => [$this->product->id => 2]]);

        $response = $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Иван Петров',
                'customer_email' => 'ivan@example.com',
            ]);

        $order = Order::query()->first();
        $this->assertNotNull($order);

        $response->assertRedirect(route('checkout.success', $order));
        $response->assertSessionHas('success', 'Заказ успешно оформлен.');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'Иван Петров',
            'customer_email' => 'ivan@example.com',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => 'Checkout Test Product',
            'quantity' => 2,
        ]);

        $this->assertSame(200.0, (float) $order->fresh()->total);
    }

    public function test_checkout_decreases_product_stock(): void
    {
        session(['cart' => [$this->product->id => 3]]);

        $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Иван Петров',
                'customer_email' => 'ivan@example.com',
            ]);

        $this->assertSame(7, $this->product->fresh()->stock);
    }

    public function test_checkout_clears_cart_after_success(): void
    {
        session(['cart' => [$this->product->id => 2]]);

        $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Иван Петров',
                'customer_email' => 'ivan@example.com',
            ]);

        $this->assertSame([], session('cart'));
    }

    public function test_checkout_validation_fails_for_missing_name_or_invalid_email(): void
    {
        session(['cart' => [$this->product->id => 1]]);

        $response = $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => '',
                'customer_email' => 'not-an-email',
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHasErrors(['customer_name', 'customer_email']);
        $this->assertSame(0, Order::query()->count());
        $this->assertEquals([$this->product->id => 1], session('cart'));
    }

    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        $this->product->update(['stock' => 2]);
        session(['cart' => [$this->product->id => 5]]);

        $response = $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Иван Петров',
                'customer_email' => 'ivan@example.com',
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHasErrors(['checkout']);
        $this->assertSame(0, Order::query()->count());
        $this->assertEquals([$this->product->id => 5], session('cart'));
        $this->assertSame(2, $this->product->fresh()->stock);
    }
}
