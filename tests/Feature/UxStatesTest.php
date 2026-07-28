<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UxStatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_page_renders_custom_view_with_catalog_link(): void
    {
        $response = $this->get('/products/999999');

        $response->assertNotFound();
        $response->assertSee('Страница не найдена');
        $response->assertSee('Вернуться в каталог');
        $response->assertSee(route('catalog.index'), false);
    }

    public function test_empty_catalog_shows_empty_state(): void
    {
        $response = $this->get(route('catalog.index'));

        $response->assertOk();
        $response->assertSee('Товары не найдены');
        $response->assertSee('Каталог пока пуст');
        $response->assertSee('php artisan db:seed --class=ProductSeeder');
        $response->assertSee('Обновить');
    }

    public function test_empty_cart_shows_description_and_catalog_link(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Корзина пуста');
        $response->assertSee('Добавьте товары из каталога');
        $response->assertSee('Перейти в каталог');
        $response->assertSee(route('catalog.index'), false);
    }

    public function test_checkout_validation_highlights_fields(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        session(['cart' => [$product->id => 1]]);

        $response = $this->from(route('checkout.index'))
            ->followingRedirects()
            ->post(route('checkout.store'), [
                'customer_name' => '',
                'customer_email' => 'not-an-email',
            ]);

        $response->assertOk();
        $response->assertSee('input-error', false);
    }

    public function test_out_of_stock_product_card_is_visually_distinct(): void
    {
        Product::factory()->create([
            'name' => 'Sold Out Item',
            'stock' => 0,
        ]);

        $response = $this->get(route('catalog.index'));

        $response->assertOk();
        $response->assertSee('Sold Out Item');
        $response->assertSee('badge-out-of-stock', false);
        $response->assertSee('product-card--out-of-stock', false);
    }
}
