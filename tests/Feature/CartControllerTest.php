<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_update_returns_404_for_nonexistent_product(): void
    {
        $response = $this->patch('/cart/999999', ['quantity' => 1]);

        $response->assertNotFound();
    }
}
