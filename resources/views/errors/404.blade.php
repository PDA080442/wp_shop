@extends('layouts.app')

@section('title', __('ux.not_found_title') . ' | ' . config('app.name'))

@section('content')
    <x-empty-state icon="not-found" :title="__('ux.not_found_title')" :description="__('ux.not_found_description')">
        <a href="{{ route('catalog.index') }}" class="btn-primary">{{ __('ux.back_to_catalog') }}</a>
    </x-empty-state>
@endsection
