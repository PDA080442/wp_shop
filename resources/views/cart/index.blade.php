@extends('layouts.app')

@section('title', 'Корзина | ' . config('app.name'))

@section('content')
    <h1 class="text-2xl font-semibold text-gray-900">Корзина</h1>

    @if ($items->isEmpty())
        <div class="mt-8 rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center">
            <p class="text-lg font-medium text-gray-900">{{ __('cart.empty') }}</p>
            <a href="{{ route('catalog.index') }}"
               class="mt-4 inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                {{ __('cart.go_to_catalog') }}
            </a>
        </div>
    @else
        <div class="mt-6 overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                            {{ __('cart.product') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                            {{ __('cart.price') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                            {{ __('cart.quantity') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                            {{ __('cart.subtotal') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                            {{ __('cart.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($items as $item)
                        @include('cart.partials.cart-item-row', ['item' => $item])
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col gap-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1 text-gray-700">
                <p>{{ __('cart.positions_count', ['count' => $positionsCount]) }}</p>
                <p class="text-xl font-semibold text-gray-900">
                    {{ __('cart.total', ['amount' => number_format($total, 2, '.', ' ') . ' ₽']) }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        {{ __('cart.clear_cart') }}
                    </button>
                </form>

                <a href="{{ route('checkout.index') }}"
                   class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    {{ __('cart.checkout') }}
                </a>
            </div>
        </div>
    @endif
@endsection
