@extends('layouts.app')

@section('title', __('checkout.title') . ' | ' . config('app.name'))

@section('content')
    <a href="{{ route('cart.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
        {{ __('checkout.back_to_cart') }}
    </a>

    <h1 class="page-title mt-4">{{ __('checkout.title') }}</h1>

    <div class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <form method="POST" action="{{ route('checkout.store') }}"
              class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
            @csrf

            <div>
                <label for="customer_name" class="block text-sm font-medium text-gray-700">
                    {{ __('checkout.customer_name') }}
                </label>
                <input type="text"
                       id="customer_name"
                       name="customer_name"
                       value="{{ old('customer_name') }}"
                       required
                       maxlength="255"
                       class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('customer_name') border-red-500 @enderror">
                @error('customer_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="customer_email" class="block text-sm font-medium text-gray-700">
                    {{ __('checkout.customer_email') }}
                </label>
                <input type="email"
                       id="customer_email"
                       name="customer_email"
                       value="{{ old('customer_email') }}"
                       required
                       maxlength="255"
                       class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('customer_email') border-red-500 @enderror">
                @error('customer_email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary mt-6 w-full justify-center">
                {{ __('checkout.confirm') }}
            </button>
        </form>

        @include('checkout.partials.order-summary', ['items' => $items, 'total' => $total])
    </div>
@endsection
