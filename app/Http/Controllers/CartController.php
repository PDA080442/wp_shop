<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\AddToCartRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index');
    }

    public function add(AddToCartRequest $request): RedirectResponse
    {
        try {
            cart()->add(
                (int) $request->validated('product_id'),
                (int) $request->validated('quantity'),
            );
        } catch (InsufficientStockException $e) {
            return back()
                ->withErrors(['quantity' => __('cart.insufficient_stock', ['stock' => $e->available])])
                ->withInput();
        }

        return back()->with('success', __('cart.added'));
    }

    public function update(): RedirectResponse
    {
        return redirect()->back();
    }

    public function remove(): RedirectResponse
    {
        return redirect()->back();
    }
}
