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

    public function index(): View
    {
        return view('checkout.index');
    }

    public function store(): RedirectResponse
    {
        return redirect()->back();
    }
}
