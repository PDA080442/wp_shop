<?php

namespace App\Contracts;

use App\Models\Order;

interface OrderServiceInterface
{
    /**
     * @param  array{customer_name: string, customer_email: string}  $customerData
     * @param  array<int, int>  $cartItems  product_id => quantity
     */
    public function createOrder(array $customerData, array $cartItems): Order;
}
