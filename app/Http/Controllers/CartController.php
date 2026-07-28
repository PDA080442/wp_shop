<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = cart()->getItems();

        return view('cart.index', [
            'items' => $items,
            'total' => cart()->getTotal(),
            'positionsCount' => $items->count(),
        ]);
    }

    public function add(AddToCartRequest $request): RedirectResponse
    {
        try {
            cart()->add(
                (int) $request->validated('product_id'),
                (int) $request->validated('quantity'),
            );
        } catch (InsufficientStockException $e) {
            return $this->stockErrorRedirect($e);
        }

        return back()->with('success', __('cart.added'));
    }

    public function update(UpdateCartRequest $request, Product $product): RedirectResponse
    {
        try {
            cart()->update($product->id, (int) $request->validated('quantity'));
        } catch (InsufficientStockException $e) {
            return $this->stockErrorRedirect($e);
        }

        return back()->with('success', __('cart.updated'));
    }

    public function remove(Product $product): RedirectResponse
    {
        cart()->remove($product->id);

        return back()->with('success', __('cart.removed'));
    }

    public function clear(): RedirectResponse
    {
        cart()->clear();

        return redirect()->route('cart.index')->with('success', __('cart.cleared'));
    }

    private function stockErrorRedirect(InsufficientStockException $e): RedirectResponse
    {
        $message = $e->available === 0
            ? __('cart.out_of_stock')
            : __('cart.insufficient_stock', ['stock' => $e->available]);

        return back()->withErrors(['quantity' => $message])->withInput();
    }
}
