<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold text-gray-900">{{ __('checkout.summary') }}</h2>

    <ul class="mt-4 divide-y divide-gray-200">
        @foreach ($items as $item)
            <li class="flex items-start justify-between gap-4 py-3 text-sm">
                <span class="text-gray-700">
                    {{ $item->name }} &times; {{ $item->quantity }}
                </span>
                <span class="whitespace-nowrap font-medium text-gray-900">
                    {{ $item->formattedSubtotal() }}
                </span>
            </li>
        @endforeach
    </ul>

    <p class="mt-4 border-t border-gray-200 pt-4 text-xl font-semibold text-gray-900">
        {{ __('checkout.total', ['amount' => number_format($total, 2, '.', ' ') . ' ₽']) }}
    </p>
</div>
