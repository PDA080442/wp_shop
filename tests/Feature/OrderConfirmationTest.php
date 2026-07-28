<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_page_shows_order_details_after_checkout(): void
    {
        $product = Product::factory()->create([
            'name' => 'Confirm Widget',
            'price' => 250.00,
            'stock' => 10,
        ]);
        session(['cart' => [$product->id => 2]]);

        $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'customer_name' => 'Анна Смирнова',
                'customer_email' => 'anna@example.com',
            ])
            ->assertRedirect();

        $order = Order::query()->first();
        $this->assertNotNull($order);

        $response = $this->get(route('checkout.success', $order));

        $response->assertOk();
        $response->assertSee('Спасибо за заказ!');
        $response->assertSee('Номер заказа: #'.$order->id);
        $response->assertSee('Анна Смирнова');
        $response->assertSee('anna@example.com');
        $response->assertSee('Confirm Widget');
        $response->assertSee('500.00 ₽');
        $response->assertSee('Продолжить покупки');
    }

    public function test_success_page_returns_404_without_session_flash(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('checkout.success', $order));

        $response->assertNotFound();
    }

    public function test_success_page_returns_404_for_wrong_order_id_in_flash(): void
    {
        $order = Order::factory()->create();

        $response = $this->withSession(['last_order_id' => $order->id + 1])
            ->get(route('checkout.success', $order));

        $response->assertNotFound();
    }

    public function test_success_page_returns_404_for_nonexistent_order(): void
    {
        $response = $this->withSession(['last_order_id' => 999999])
            ->get('/checkout/success/999999');

        $response->assertNotFound();
    }
}
