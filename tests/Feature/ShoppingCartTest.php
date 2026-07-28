<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create([
            'name' => 'Cart Test Product',
            'price' => 100.00,
            'stock' => 10,
        ]);
    }

    public function test_add_to_cart_stores_item_in_session_and_redirects_back(): void
    {
        $response = $this->from(route('products.show', $this->product))
            ->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

        $response->assertRedirect(route('products.show', $this->product));
        $response->assertSessionHas('success', 'Товар добавлен в корзину.');
        $this->assertEquals(2, session('cart')[$this->product->id]);
    }

    public function test_cart_page_displays_added_items_with_correct_total(): void
    {
        $this->from(route('products.show', $this->product))
            ->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Cart Test Product');
        $response->assertSee('100.00 ₽');
        $response->assertSee('200.00 ₽');
        $response->assertSee('Итого: 200.00 ₽');
    }

    public function test_update_cart_item_recalculates_total(): void
    {
        $this->from(route('products.show', $this->product))
            ->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

        $this->from(route('cart.index'))
            ->patch(route('cart.update', $this->product), ['quantity' => 5]);

        $this->assertEquals(5, session('cart')[$this->product->id]);

        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('500.00 ₽');
        $response->assertSee('Итого: 500.00 ₽');
    }

    public function test_remove_cart_item_deletes_position(): void
    {
        $this->from(route('products.show', $this->product))
            ->post(route('cart.add'), [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

        $response = $this->from(route('cart.index'))
            ->delete(route('cart.remove', $this->product));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Товар удалён из корзины.');
        $this->assertArrayNotHasKey($this->product->id, session('cart', []));
    }

    public function test_clear_cart_removes_all_items(): void
    {
        $secondProduct = Product::factory()->create([
            'name' => 'Second Cart Product',
            'price' => 50.00,
            'stock' => 10,
        ]);

        session(['cart' => [$this->product->id => 1, $secondProduct->id => 2]]);

        $response = $this->from(route('cart.index'))
            ->delete(route('cart.clear'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Корзина очищена.');
        $this->assertSame([], session('cart'));
    }

    public function test_empty_cart_shows_message(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Корзина пуста');
    }
}
