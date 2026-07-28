<?php

namespace App\Http\Controllers;

use App\Contracts\OrderServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService,
    ) {}

    public function index(): View|RedirectResponse
    {
        $items = cart()->getItems();

        if ($items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('success', __('checkout.cart_empty_redirect'));
        }

        return view('checkout.index', [
            'items' => $items,
            'total' => cart()->getTotal(),
        ]);
    }

    public function store(): RedirectResponse
    {
        return redirect()->back();
    }
}
