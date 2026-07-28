<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddToCartValidationTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_cumulative_add_rejects_when_total_exceeds_stock(): void
    {
        $product = Product::factory()->create(['stock' => 5]);
        session(['cart' => [$product->id => 3]]);

        $response = $this->from(route('products.show', $product))
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 3,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHasErrors(['quantity' => 'Доступно только 5 шт.']);
        $this->assertEquals(3, session('cart')[$product->id]);
    }
}
