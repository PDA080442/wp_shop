<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['customer_name', 'customer_email', 'total'])]
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotal(): float
    {
        return (float) $this->items->sum(
            fn (OrderItem $item) => (float) $item->price * $item->quantity
        );
    }

    public function recalculateTotal(): self
    {
        $this->total = $this->calculateTotal();
        $this->save();

        return $this;
    }

    public function formattedTotal(): string
    {
        return number_format((float) $this->total, 2, '.', ' ').' ₽';
    }
}
