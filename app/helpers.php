<?php

use App\Contracts\CartServiceInterface;

if (! function_exists('cart')) {
    function cart(): CartServiceInterface
    {
        return app(CartServiceInterface::class);
    }
}

if (! function_exists('money')) {
    function money(float|string $amount): string
    {
        return number_format((float) $amount, 2, '.', ' ').' ₽';
    }
}
