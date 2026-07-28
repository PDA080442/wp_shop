<?php

namespace App\Http\Controllers;

use App\Contracts\ProductServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
    ) {}

    public function index(): View
    {
        return view('cart.index');
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ], [
            'product_id.exists' => 'Товар не найден.',
            'quantity.min' => 'Количество должно быть не меньше 1.',
        ]);

        $product = $this->productService->getById($validated['product_id']);

        if (! $product->isInStock()) {
            return back()->withErrors(['quantity' => 'Товара нет в наличии.'])->withInput();
        }

        if ($validated['quantity'] > $product->stock) {
            return back()
                ->withErrors(['quantity' => "Доступно только {$product->stock} шт."])
                ->withInput();
        }

        return back()->with('success', 'Товар добавлен в корзину.');
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
