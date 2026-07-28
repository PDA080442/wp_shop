<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddToCartValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_add_product_with_zero_stock(): void
    {
        $product = Product::factory()->create(['stock' => 0]);

        $response = $this->from(route('products.show', $product))
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHasErrors(['quantity' => 'Товара нет в наличии.']);
    }

    public function test_cannot_add_quantity_greater_than_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);

        $response = $this->from(route('products.show', $product))
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 10,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHasErrors(['quantity' => 'Доступно только 3 шт.']);
    }

    public function test_cannot_add_zero_quantity(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->from(route('products.show', $product))
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 0,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHasErrors(['quantity']);
    }

    public function test_cannot_add_nonexistent_product(): void
    {
        $response = $this->from(route('catalog.index'))
            ->post(route('cart.add'), [
                'product_id' => 999999,
                'quantity' => 1,
            ]);

        $response->assertRedirect(route('catalog.index'));
        $response->assertSessionHasErrors(['product_id']);
    }

    public function test_valid_request_adds_to_cart(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->from(route('products.show', $product))
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHas('success', 'Товар добавлен в корзину.');
        $response->assertSessionHasNoErrors();
        $this->assertEquals(2, session('cart')[$product->id]);
    }
}
