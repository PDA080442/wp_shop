@extends('layouts.app')

@section('title', __('ux.server_error_title') . ' | ' . config('app.name'))

@section('content')
    <x-empty-state icon="server" :title="__('ux.server_error_title')" :description="__('ux.server_error_description')">
        <a href="{{ route('catalog.index') }}" class="btn-primary">{{ __('ux.back_to_catalog') }}</a>
    </x-empty-state>
@endsection
