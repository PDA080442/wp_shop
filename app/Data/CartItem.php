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

    public function formattedPrice(): string
    {
        return number_format((float) $this->price, 2, '.', ' ').' ₽';
    }

    public function formattedSubtotal(): string
    {
        return number_format($this->subtotal, 2, '.', ' ').' ₽';
    }

    public function exceedsStock(): bool
    {
        return $this->quantity > $this->stock;
    }
}
