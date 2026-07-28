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
        <x-empty-state icon="catalog" :title="__('ux.catalog_empty_title')" :description="__('ux.catalog_empty_description')">
            <a href="{{ route('catalog.index') }}" class="btn-primary">{{ __('ux.refresh') }}</a>
            <div class="mt-4 font-mono text-sm text-gray-700">
                <p>{{ __('ux.catalog_empty_hint') }}</p>
            </div>
        </x-empty-state>
    @endif
@endsection
