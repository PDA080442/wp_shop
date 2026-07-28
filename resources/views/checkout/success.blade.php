@extends('layouts.app')

@section('title', __('checkout.success_title') . ' | ' . config('app.name'))

@section('content')
    <div class="mx-auto max-w-3xl rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
        <div class="text-center">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('checkout.success_title') }}</h1>
            <p class="mt-2 text-lg text-gray-700">
                {{ __('checkout.order_number', ['id' => $order->id]) }}
            </p>
        </div>

        <div class="mt-8 rounded-lg border border-gray-100 bg-gray-50 p-4">
            <h2 class="text-sm font-medium uppercase tracking-wide text-gray-500">
                {{ __('checkout.recipient') }}
            </h2>
            <p class="mt-2 text-gray-900">{{ $order->customer_name }}</p>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('checkout.email_notification', ['email' => $order->customer_email]) }}
            </p>
        </div>

        <div class="mt-8">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">{{ __('checkout.order_items') }}</h2>
            @include('checkout.partials.order-items-table', ['order' => $order])
        </div>

        <p class="mt-6 text-right text-xl font-semibold text-gray-900">
            {{ __('checkout.total', ['amount' => $order->formattedTotal()]) }}
        </p>

        <div class="mt-8 text-center">
            <a href="{{ route('catalog.index') }}"
               class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                {{ __('checkout.continue_shopping') }}
            </a>
        </div>
    </div>
@endsection
