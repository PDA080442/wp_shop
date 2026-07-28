<?php

namespace App\Data;

readonly class CartItem
{
    public function __construct(
        public int $productId,
        public string $name,
        public string $price,
        public int $stock,
        public int $quantity,
        public float $subtotal,
    ) {}
}
