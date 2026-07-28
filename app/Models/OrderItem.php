<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'product_id', 'product_name', 'price', 'quantity'])]
class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (OrderItem $item) {
            $item->order?->recalculateTotal();
        });

        static::deleted(function (OrderItem $item) {
            $item->order?->recalculateTotal();
        });
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function subtotal(): float
    {
        return (float) $this->price * $this->quantity;
    }

    public function formattedPrice(): string
    {
        return number_format((float) $this->price, 2, '.', ' ').' ₽';
    }

    public function formattedSubtotal(): string
    {
        return number_format($this->subtotal(), 2, '.', ' ').' ₽';
    }
}
