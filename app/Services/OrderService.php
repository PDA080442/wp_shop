<?php

namespace App\Services;

use App\Contracts\OrderServiceInterface;
use App\Exceptions\InsufficientStockException;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderService implements OrderServiceInterface
{
    /**
     * @param  array{customer_name: string, customer_email: string}  $customerData
     * @param  array<int, int>  $cartItems
     */
    public function createOrder(array $customerData, array $cartItems): Order
    {
        if ($cartItems === []) {
            throw new InvalidArgumentException('Корзина пуста');
        }

        return DB::transaction(function () use ($customerData, $cartItems) {
            $products = Product::query()
                ->whereIn('id', array_keys($cartItems))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cartItems as $productId => $quantity) {
                $product = $products->get($productId);

                if ($product === null) {
                    throw new InsufficientStockException(
                        (int) $productId,
                        'Неизвестный товар',
                        $quantity,
                        0,
                    );
                }

                if ($product->stock < $quantity) {
                    throw new InsufficientStockException(
                        $product->id,
                        $product->name,
                        $quantity,
                        $product->stock,
                    );
                }
            }

            $order = Order::create([
                'customer_name' => $customerData['customer_name'],
                'customer_email' => $customerData['customer_email'],
                'total' => 0,
            ]);

            foreach ($cartItems as $productId => $quantity) {
                $product = $products->get($productId);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                ]);

                $product->decrement('stock', $quantity);
            }

            return $order->fresh(['items']);
        });
    }
}
