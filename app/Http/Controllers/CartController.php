<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index');
    }

    public function add(): RedirectResponse
    {
        return redirect()->back();
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
