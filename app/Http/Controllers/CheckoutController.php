<?php

namespace App\Http\Controllers;

use App\Contracts\OrderServiceInterface;
use App\Exceptions\InsufficientStockException;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
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

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cartItems = cart()->contents();

        if ($cartItems === []) {
            return redirect()
                ->route('cart.index')
                ->with('success', __('checkout.cart_empty_redirect'));
        }

        try {
            $order = $this->orderService->createOrder(
                $request->validated(),
                $cartItems,
            );
        } catch (InsufficientStockException $e) {
            $message = $e->available === 0
                ? __('checkout.stock_unavailable', ['name' => $e->productName])
                : __('checkout.stock_changed', [
                    'name' => $e->productName,
                    'stock' => $e->available,
                ]);

            return back()->withErrors(['checkout' => $message])->withInput();
        }

        cart()->clear();

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', __('checkout.order_created'));
    }

    public function success(Order $order): View
    {
        return view('checkout.success', compact('order'));
    }
}
