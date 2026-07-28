@extends('layouts.app')

@section('title', 'Корзина | ' . config('app.name'))

@section('content')
    <h1 class="page-title">Корзина</h1>

    @if ($items->isEmpty())
        <x-empty-state icon="cart" :title="__('cart.empty')" :description="__('ux.cart_empty_description')">
            <a href="{{ route('catalog.index') }}" class="btn-primary">{{ __('cart.go_to_catalog') }}</a>
        </x-empty-state>
    @else
        <div class="mt-6 overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
                            {{ __('cart.product') }}
                        </th>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
                            {{ __('cart.price') }}
                        </th>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
                            {{ __('cart.quantity') }}
                        </th>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
                            {{ __('cart.subtotal') }}
                        </th>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
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

        <div class="mt-6 flex flex-col gap-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
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

                <a href="{{ route('checkout.index') }}" class="btn-primary">
                    {{ __('cart.checkout') }}
                </a>
            </div>
        </div>
    @endif
@endsection
