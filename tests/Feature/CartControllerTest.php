<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_changes_quantity_in_session(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        session(['cart' => [$product->id => 2]]);

        $response = $this->from(route('cart.index'))
            ->patch(route('cart.update', $product), ['quantity' => 5]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Количество обновлено.');
        $this->assertEquals(5, session('cart')[$product->id]);
    }

    public function test_update_rejects_quantity_greater_than_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);
        session(['cart' => [$product->id => 1]]);

        $response = $this->from(route('cart.index'))
            ->patch(route('cart.update', $product), ['quantity' => 10]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHasErrors(['quantity' => 'Доступно только 3 шт.']);
    }

    public function test_update_rejects_product_with_zero_stock(): void
    {
        $product = Product::factory()->create(['stock' => 0]);
        session(['cart' => [$product->id => 1]]);

        $response = $this->from(route('cart.index'))
            ->patch(route('cart.update', $product), ['quantity' => 1]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHasErrors(['quantity' => 'Товара нет в наличии.']);
    }

    public function test_remove_deletes_item_from_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        session(['cart' => [$product->id => 2]]);

        $response = $this->from(route('cart.index'))
            ->delete(route('cart.remove', $product));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Товар удалён из корзины.');
        $this->assertArrayNotHasKey($product->id, session('cart', []));
    }

    public function test_clear_empties_cart_and_redirects_to_index(): void
    {
        $first = Product::factory()->create(['stock' => 10]);
        $second = Product::factory()->create(['stock' => 10]);
        session(['cart' => [$first->id => 1, $second->id => 2]]);

        $response = $this->from(route('cart.index'))
            ->delete(route('cart.clear'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Корзина очищена.');
        $this->assertSame([], session('cart'));
    }

    public function test_update_returns_404_for_nonexistent_product(): void
    {
        $response = $this->patch('/cart/999999', ['quantity' => 1]);

        $response->assertNotFound();
    }
}
