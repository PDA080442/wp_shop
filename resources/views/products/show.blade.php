@extends('layouts.app')

@section('title', $product->name . ' | ' . config('app.name'))

@section('content')
    <a href="{{ route('catalog.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
        ← Назад в каталог
    </a>

    <article class="mt-4 max-w-2xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h1>

        <p class="mt-4 text-3xl font-medium text-gray-900">
            {{ $product->formattedPrice() }}
        </p>

        <div class="mt-4">
            @if ($product->isInStock())
                <p class="text-sm text-gray-600">В наличии: {{ $product->stock }} шт.</p>
            @else
                <span class="badge-out-of-stock">Нет в наличии</span>
                <p class="mt-2 text-sm text-gray-600">Этот товар сейчас недоступен для заказа.</p>
            @endif
        </div>

        @if ($product->isInStock())
            <form method="POST" action="{{ route('cart.add') }}" class="mt-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Количество</label>
                        <input type="number"
                               id="quantity"
                               name="quantity"
                               value="{{ old('quantity', 1) }}"
                               min="1"
                               max="{{ $product->stock }}"
                               required
                               class="mt-1 block w-24 rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900 @error('quantity') border-red-500 @enderror">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                        В корзину
                    </button>
                </div>
            </form>
        @else
            <button type="button" disabled
                    class="mt-6 inline-flex cursor-not-allowed items-center rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-500">
                В корзину
            </button>
        @endif
    </article>
@endsection
