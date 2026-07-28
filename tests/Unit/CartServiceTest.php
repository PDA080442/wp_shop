<?php

namespace Tests\Unit;

use App\Contracts\CartServiceInterface;
use App\Data\CartItem;
use App\Exceptions\InsufficientStockException;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private CartServiceInterface $cart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cart = app(CartServiceInterface::class);
    }

    public function test_add_persists_to_session_and_calculates_totals(): void
    {
        $product = Product::factory()->create(['price' => 100.00, 'stock' => 10]);

        $this->cart->add($product->id, 2);

        $this->assertEquals([$product->id => 2], session('cart'));
        $this->assertSame(2, $this->cart->count());
        $this->assertSame(200.0, $this->cart->getTotal());
    }

    public function test_add_accumulates_quantity(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->cart->add($product->id, 2);
        $this->cart->add($product->id, 3);

        $this->assertEquals([$product->id => 5], session('cart'));
        $this->assertSame(5, $this->cart->count());
    }

    public function test_add_throws_when_cumulative_quantity_exceeds_stock(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->cart->add($product->id, 3);

        $this->expectException(InsufficientStockException::class);

        $this->cart->add($product->id, 3);
    }

    public function test_update_sets_absolute_quantity(): void
    {
        $product = Product::factory()->create(['price' => 50.00, 'stock' => 10]);

        $this->cart->add($product->id, 2);
        $this->cart->update($product->id, 4);

        $this->assertEquals([$product->id => 4], session('cart'));
        $this->assertSame(200.0, $this->cart->getTotal());
    }

    public function test_update_with_zero_quantity_removes_item(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->cart->add($product->id, 2);
        $this->cart->update($product->id, 0);

        $this->assertSame([], session('cart'));
        $this->assertSame(0, $this->cart->count());
    }

    public function test_remove_deletes_item_from_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->cart->add($product->id, 2);
        $this->cart->remove($product->id);

        $this->assertSame([], session('cart'));
    }

    public function test_clear_empties_cart(): void
    {
        $first = Product::factory()->create(['stock' => 10]);
        $second = Product::factory()->create(['stock' => 10]);

        $this->cart->add($first->id, 1);
        $this->cart->add($second->id, 2);
        $this->cart->clear();

        $this->assertSame([], session('cart'));
        $this->assertSame(0.0, $this->cart->getTotal());
    }

    public function test_get_items_returns_cart_item_dto_with_subtotal(): void
    {
        $product = Product::factory()->create(['price' => 99.50, 'stock' => 10]);

        $this->cart->add($product->id, 3);

        $items = $this->cart->getItems();

        $this->assertCount(1, $items);
        $item = $items->first();
        $this->assertInstanceOf(CartItem::class, $item);
        $this->assertSame($product->id, $item->productId);
        $this->assertSame($product->name, $item->name);
        $this->assertSame('99.50', $item->price);
        $this->assertSame(10, $item->stock);
        $this->assertSame(3, $item->quantity);
        $this->assertSame(298.5, $item->subtotal);
    }

    public function test_get_items_removes_stale_product_from_session(): void
    {
        session(['cart' => [999999 => 2]]);

        $items = $this->cart->getItems();

        $this->assertCount(0, $items);
        $this->assertSame([], session('cart'));
    }
}
