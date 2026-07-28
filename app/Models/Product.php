<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'price', 'stock', 'image'])]
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

    public function imageUrl(): string
    {
        if (blank($this->image)) {
            return $this->placeholderUrl();
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (! is_file(public_path($this->image))) {
            return $this->placeholderUrl();
        }

        return asset($this->image);
    }

    protected function placeholderUrl(): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($this->placeholderSvg());
    }

    /**
     * Deterministic gradient placeholder so the catalog works without external image hosts.
     */
    protected function placeholderSvg(): string
    {
        $hue = crc32((string) ($this->id ?: $this->name)) % 360;
        $label = htmlspecialchars(mb_strtoupper(mb_substr((string) $this->name, 0, 1)), ENT_XML1);

        return <<<SVG
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 450" width="600" height="450">
                <defs>
                    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="hsl({$hue}, 55%, 90%)"/>
                        <stop offset="1" stop-color="hsl({$hue}, 45%, 58%)"/>
                    </linearGradient>
                </defs>
                <rect width="600" height="450" fill="url(#g)"/>
                <text x="300" y="235" text-anchor="middle" dominant-baseline="central"
                      font-family="system-ui, sans-serif" font-size="200" font-weight="600"
                      fill="#ffffff" fill-opacity="0.75">{$label}</text>
            </svg>
            SVG;
    }
}
