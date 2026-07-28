<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_redirects_to_cart_when_empty(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Сначала добавьте товары в корзину.');
    }

    public function test_checkout_page_displays_form_and_order_summary(): void
    {
        $product = Product::factory()->create([
            'name' => 'Checkout Widget',
            'price' => 200.00,
            'stock' => 10,
        ]);
        session(['cart' => [$product->id => 2]]);

        $response = $this->get(route('checkout.index'));

        $response->assertOk();
        $response->assertSee('Оформление заказа');
        $response->assertSee('Имя получателя');
        $response->assertSee('Email');
        $response->assertSee('name="customer_name"', false);
        $response->assertSee('name="customer_email"', false);
        $response->assertSee('Сводка заказа');
        $response->assertSee('Checkout Widget');
        $response->assertSee('400.00 ₽');
        $response->assertSee('Подтвердить заказ');
        $response->assertSee('← Вернуться в корзину');
        $response->assertSee(route('cart.index'), false);
        $response->assertSee(route('checkout.store'), false);
    }
}
