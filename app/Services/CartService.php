<?php

namespace App\Services;

use App\Contracts\CartServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\Data\CartItem;
use App\Exceptions\InsufficientStockException;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartService implements CartServiceInterface
{
    /** @var array<int, int>|null */
    private ?array $cart = null;

    public function __construct(
        private readonly ProductServiceInterface $productService,
    ) {}

    public function add(int $productId, int $quantity): void
    {
        $product = $this->productService->getById($productId);
        $cart = $this->loadCart();
        $newQuantity = ($cart[$productId] ?? 0) + $quantity;

        $this->assertCanOrder($product, $newQuantity);

        $cart[$productId] = $newQuantity;
        $this->persist($cart);
    }

    public function update(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($productId);

            return;
        }

        $product = $this->productService->getById($productId);
        $this->assertCanOrder($product, $quantity);

        $cart = $this->loadCart();
        $cart[$productId] = $quantity;
        $this->persist($cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->loadCart();
        unset($cart[$productId]);
        $this->persist($cart);
    }

    public function clear(): void
    {
        $this->persist([]);
    }

    public function getItems(): Collection
    {
        $cart = $this->loadCart();

        if ($cart === []) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect();
        $staleIds = [];

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if ($product === null) {
                $staleIds[] = $productId;

                continue;
            }

            $items->push(new CartItem(
                productId: $product->id,
                name: $product->name,
                price: (string) $product->price,
                stock: $product->stock,
                quantity: $quantity,
                subtotal: (float) $product->price * $quantity,
            ));
        }

        if ($staleIds !== []) {
            $cleanCart = $cart;
            foreach ($staleIds as $staleId) {
                unset($cleanCart[$staleId]);
            }
            $this->persist($cleanCart);
        }

        return $items;
    }

    public function getTotal(): float
    {
        return (float) $this->getItems()->sum('subtotal');
    }

    public function count(): int
    {
        return array_sum($this->loadCart());
    }

    public function contents(): array
    {
        return $this->loadCart();
    }

    /**
     * @return array<int, int>
     */
    private function loadCart(): array
    {
        return $this->cart ??= session('cart', []);
    }

    /**
     * @param  array<int, int>  $cart
     */
    private function persist(array $cart): void
    {
        $this->cart = $cart;
        session(['cart' => $cart]);
    }

    private function assertCanOrder(Product $product, int $quantity): void
    {
        if (! $product->isInStock()) {
            throw new InsufficientStockException(
                $product->id,
                $product->name,
                $quantity,
                0,
            );
        }

        if (! $product->canOrder($quantity)) {
            throw new InsufficientStockException(
                $product->id,
                $product->name,
                $quantity,
                $product->stock,
            );
        }
    }
}
