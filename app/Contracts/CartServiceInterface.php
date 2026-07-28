<?php

namespace App\Contracts;

use App\Data\CartItem;
use Illuminate\Support\Collection;

interface CartServiceInterface
{
    public function add(int $productId, int $quantity): void;

    public function update(int $productId, int $quantity): void;

    public function remove(int $productId): void;

    public function clear(): void;

    /**
     * @return Collection<int, CartItem>
     */
    public function getItems(): Collection;

    public function getTotal(): float;

    public function count(): int;

    /**
     * @return array<int, int>
     */
    public function contents(): array;
}
