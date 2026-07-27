@extends('layouts.app')

@section('title', 'Товар #' . $id . ' | ' . config('app.name'))

@section('content')
    <h1 class="text-2xl font-semibold">Карточка товара #{{ $id }}</h1>
@endsection
