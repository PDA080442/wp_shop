@extends('layouts.app')

@section('title', 'Каталог | ' . config('app.name'))

@section('content')
    <h1 class="page-title">Каталог товаров</h1>

    @if ($products->count() > 0)
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="mt-8 rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center">
            <p class="text-lg font-medium text-gray-900">Товары не найдены</p>
            <p class="mt-2 text-gray-600">Заполните каталог тестовыми данными:</p>
            <div class="mt-4 space-y-1 font-mono text-sm text-gray-700">
                <p>php artisan tinker</p>
                <p>Product::factory()-&gt;count(50)-&gt;create();</p>
            </div>
        </div>
    @endif
@endsection
