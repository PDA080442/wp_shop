<?php

namespace App\Http\Controllers;

use App\Contracts\ProductServiceInterface;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
    ) {}

    public function index(): View
    {
        return view('catalog.index');
    }

    public function show(int $id): View
    {
        $product = $this->productService->getById($id);

        return view('products.show', compact('product'));
    }
}
