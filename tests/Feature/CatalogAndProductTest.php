<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogAndProductTest extends TestCase
{
    use RefreshDatabase;

    private Product $inStockProduct;

    private Product $outOfStockProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inStockProduct = Product::factory()->create([
            'name' => 'Catalog Widget Alpha',
            'price' => 1234.56,
            'stock' => 10,
        ]);

        $this->outOfStockProduct = Product::factory()->outOfStock()->create([
            'name' => 'Catalog Widget Sold Out',
        ]);
    }

    public function test_catalog_index_returns_200_and_lists_products_from_database(): void
    {
        $response = $this->get(route('catalog.index'));

        $response->assertOk();
        $response->assertSee('Каталог товаров');
        $response->assertSee($this->inStockProduct->name);
        $response->assertSee($this->outOfStockProduct->name);
    }

    public function test_product_show_returns_200_with_product_details(): void
    {
        $response = $this->get(route('products.show', $this->inStockProduct));

        $response->assertOk();
        $response->assertSee($this->inStockProduct->name);
        $response->assertSee($this->inStockProduct->formattedPrice());
        $response->assertSee('В наличии: '.$this->inStockProduct->stock.' шт.');
    }

    public function test_product_show_returns_404_for_nonexistent_product(): void
    {
        $response = $this->get('/products/999999');

        $response->assertNotFound();
    }

    public function test_cannot_add_out_of_stock_product_to_cart(): void
    {
        $response = $this->from(route('products.show', $this->outOfStockProduct))
            ->post(route('cart.add'), [
                'product_id' => $this->outOfStockProduct->id,
                'quantity' => 1,
            ]);

        $response->assertRedirect(route('products.show', $this->outOfStockProduct));
        $response->assertSessionHasErrors(['quantity' => 'Товара нет в наличии.']);
    }

    public function test_cannot_add_quantity_greater_than_stock_to_cart(): void
    {
        $response = $this->from(route('products.show', $this->inStockProduct))
            ->post(route('cart.add'), [
                'product_id' => $this->inStockProduct->id,
                'quantity' => $this->inStockProduct->stock + 1,
            ]);

        $response->assertRedirect(route('products.show', $this->inStockProduct));
        $response->assertSessionHasErrors([
            'quantity' => 'Доступно только '.$this->inStockProduct->stock.' шт.',
        ]);
    }
}
