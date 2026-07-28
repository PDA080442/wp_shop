@extends('layouts.app')

@section('title', __('checkout.success_title') . ' | ' . config('app.name'))

@section('content')
    <div class="mx-auto max-w-lg rounded-lg border border-gray-200 bg-white p-8 text-center shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('checkout.success_title') }}</h1>

        <p class="mt-4 text-lg text-gray-700">
            {{ __('checkout.order_number', ['id' => $order->id]) }}
        </p>

        <a href="{{ route('catalog.index') }}"
           class="mt-6 inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            {{ __('checkout.continue_shopping') }}
        </a>
    </div>
@endsection
