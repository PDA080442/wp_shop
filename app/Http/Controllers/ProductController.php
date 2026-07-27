<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('catalog.index');
    }

    public function show(int $id): View
    {
        return view('products.show', compact('id'));
    }
}
