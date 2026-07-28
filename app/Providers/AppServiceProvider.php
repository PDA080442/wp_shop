<?php

namespace App\Providers;

use App\Contracts\OrderServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('layouts.partials.header', function ($view) {
            $cart = session('cart', []);
            $view->with('cartCount', is_array($cart) ? array_sum($cart) : 0);
        });
    }
}
