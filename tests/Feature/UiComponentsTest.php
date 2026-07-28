<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UiComponentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_shows_cart_badge_after_add_to_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->from(route('products.show', $product))
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHas('success', 'Товар добавлен в корзину.');

        $page = $this->get(route('products.show', $product));
        $page->assertOk();
        $page->assertSee('cart-badge', false);
        $page->assertSee('>1</span>', false);
    }

    public function test_header_hides_cart_badge_when_cart_empty(): void
    {
        $response = $this->get(route('catalog.index'));

        $response->assertOk();
        $response->assertDontSee('cart-badge');
    }

    public function test_flash_success_message_displayed(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->from(route('products.show', $product))
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

        $response->assertRedirect(route('products.show', $product));

        $page = $this->get(route('products.show', $product));
        $page->assertOk();
        $page->assertSee('Товар добавлен в корзину.');
        $page->assertSee('border-green-200', false);
    }

    public function test_catalog_renders_product_card_component(): void
    {
        $product = Product::factory()->create([
            'name' => 'UI Widget',
            'price' => 199.50,
            'stock' => 5,
        ]);

        $response = $this->get(route('catalog.index'));

        $response->assertOk();
        $response->assertSee('UI Widget');
        $response->assertSee('199.50 ₽');
        $response->assertSee('В корзину');
    }
}
