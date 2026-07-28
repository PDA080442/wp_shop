<?php

use App\Contracts\CartServiceInterface;

if (! function_exists('cart')) {
    function cart(): CartServiceInterface
    {
        return app(CartServiceInterface::class);
    }
}
