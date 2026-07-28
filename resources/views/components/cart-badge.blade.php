@props(['count' => 0])

@if ($count > 0)
    <span {{ $attributes->merge(['class' => 'cart-badge']) }}>{{ $count }}</span>
@endif
