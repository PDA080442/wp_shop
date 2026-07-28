@extends('layouts.app')

@section('title', $product->name . ' | ' . config('app.name'))

@section('content')
    <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
    <p class="mt-2 text-gray-600">Карточка товара #{{ $product->id }}</p>
@endsection
