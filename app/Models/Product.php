<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'price', 'stock'])]
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function canOrder(int $quantity): bool
    {
        return $quantity > 0 && $quantity <= $this->stock;
    }

    public function formattedPrice(): string
    {
        return number_format((float) $this->price, 2, '.', ' ').' ₽';
    }
}
