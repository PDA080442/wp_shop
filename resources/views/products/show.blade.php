@extends('layouts.app')

@section('title', $product->name . ' | ' . config('app.name'))

@section('main_class', 'container mx-auto flex-1 px-4 py-5 sm:px-6')

@section('content')
    <div class="flex flex-col gap-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('catalog.index') }}" class="transition hover:text-gray-900">Каталог</a>
            <span class="text-gray-300">/</span>
            <span class="truncate text-gray-900">{{ $product->name }}</span>
        </nav>

        <article class="grid grid-cols-1 overflow-hidden rounded-2xl bg-white ring-1 ring-gray-200/80 lg:grid-cols-[1.05fr_1fr]">
            <div class="relative min-h-60 bg-gray-100 lg:min-h-[28rem]">
                <img src="{{ $product->imageUrl() }}"
                     alt="{{ $product->name }}"
                     @class([
                         'absolute inset-0 h-full w-full object-cover',
                         'grayscale' => ! $product->isInStock(),
                     ])
                     width="800"
                     height="600">

                @unless ($product->isInStock())
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-900/45">
                        <span class="rounded-full bg-white/95 px-4 py-2 text-sm font-semibold tracking-wide text-gray-900 shadow-sm">
                            Нет в наличии
                        </span>
                    </div>
                @endunless
            </div>

            <div class="flex flex-col gap-6 p-6 sm:p-8 lg:justify-center">
                <div class="space-y-3">
                    @if ($product->isInStock())
                        <span class="badge-in-stock">
                            <span class="status-dot"></span>
                            В наличии: {{ $product->stock }} шт.
                        </span>
                    @else
                        <span class="badge-out-of-stock">
                            <span class="status-dot"></span>
                            Нет в наличии
                        </span>
                    @endif

                    <h1 class="text-2xl font-semibold leading-tight tracking-tight text-gray-900 sm:text-3xl">
                        {{ $product->name }}
                    </h1>

                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400">
                        Артикул SKU-{{ str_pad((string) $product->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <div class="flex items-baseline gap-3 border-y border-gray-100 py-5">
                    <span class="text-4xl font-semibold tracking-tight text-gray-900">
                        {{ $product->formattedPrice() }}
                    </span>
                    <span class="text-sm text-gray-500">за штуку</span>
                </div>

                @if ($product->isInStock())
                    <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="flex flex-wrap items-end gap-4">
                            <div>
                                <label for="quantity" class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Количество
                                </label>

                                <div class="flex items-center overflow-hidden rounded-lg ring-1 ring-gray-300 focus-within:ring-2 focus-within:ring-primary">
                                    <button type="button" data-quantity-step="-1" class="stepper-btn" aria-label="Уменьшить количество">&minus;</button>

                                    <input type="number"
                                           id="quantity"
                                           name="quantity"
                                           value="{{ old('quantity', 1) }}"
                                           min="1"
                                           max="{{ $product->stock }}"
                                           required
                                           class="h-10 w-16 border-x border-gray-200 text-center text-sm font-medium text-gray-900 focus:outline-none @error('quantity') text-red-600 @enderror">

                                    <button type="button" data-quantity-step="1" class="stepper-btn" aria-label="Увеличить количество">+</button>
                                </div>
                            </div>

                            <button type="submit" class="btn-primary h-10 min-w-44 justify-center rounded-lg">
                                В корзину
                            </button>
                        </div>

                        @error('quantity')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="text-sm text-gray-500">Доступно к заказу: {{ $product->stock }} шт.</p>
                        @enderror
                    </form>
                @else
                    <div class="space-y-4">
                        <div class="flex gap-3 rounded-lg bg-rose-50 p-4 ring-1 ring-rose-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="h-5 w-5 shrink-0 text-rose-600" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <div class="text-sm">
                                <p class="font-medium text-rose-900">Товар закончился</p>
                                <p class="mt-1 text-rose-800">Остаток на складе — 0 шт. Добавление в корзину недоступно.</p>
                            </div>
                        </div>

                        <button type="button" disabled class="btn-disabled w-full sm:w-auto sm:min-w-44">
                            В корзину
                        </button>

                        <a href="{{ route('catalog.index') }}" class="block text-sm text-gray-600 underline-offset-4 transition hover:text-gray-900 hover:underline">
                            Посмотреть другие товары
                        </a>
                    </div>
                @endif

                <dl class="grid grid-cols-3 gap-3 border-t border-gray-100 pt-5 text-xs text-gray-500">
                    <div>
                        <dt class="font-medium text-gray-900">Доставка</dt>
                        <dd class="mt-1">1–3 дня по городу</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">Оплата</dt>
                        <dd class="mt-1">Картой или наличными</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">Возврат</dt>
                        <dd class="mt-1">14 дней без причины</dd>
                    </div>
                </dl>
            </div>
        </article>
    </div>
@endsection
