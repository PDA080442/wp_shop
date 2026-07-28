<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_displays_items_and_checkout_button(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Widget',
            'price' => 150.00,
            'stock' => 10,
        ]);
        session(['cart' => [$product->id => 2]]);

        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Test Widget');
        $response->assertSee('150.00 ₽');
        $response->assertSee('300.00 ₽');
        $response->assertSee('Позиций: 1');
        $response->assertSee('Оформить заказ');
        $response->assertSee(route('checkout.index'), false);
    }

    public function test_cart_page_shows_stock_warning_when_quantity_exceeds_stock(): void
    {
        $product = Product::factory()->create([
            'name' => 'Limited Item',
            'stock' => 2,
        ]);
        session(['cart' => [$product->id => 5]]);

        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Limited Item');
        $response->assertSee('На складе только 2 шт. Уменьшите количество для «Limited Item».');
    }

    public function test_cart_page_shows_out_of_stock_message_when_stock_is_zero(): void
    {
        $product = Product::factory()->create([
            'name' => 'Gone Product',
            'stock' => 0,
        ]);
        session(['cart' => [$product->id => 3]]);

        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Товар «Gone Product» больше не доступен. Удалите позицию из корзины.');
    }
}
