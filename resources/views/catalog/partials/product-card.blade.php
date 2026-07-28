<article @class([
    'product-card flex flex-col',
    'opacity-60' => ! $product->isInStock(),
])>
    <div class="flex-1">
        <a href="{{ route('products.show', $product->id) }}" class="text-lg font-semibold text-gray-900 hover:text-gray-700">
            {{ $product->name }}
        </a>

        <p class="mt-2 text-xl font-medium text-gray-900">
            {{ $product->formattedPrice() }}
        </p>

        <div class="mt-2">
            @if ($product->isInStock())
                <p class="text-sm text-gray-600">В наличии: {{ $product->stock }} шт.</p>
            @else
                <span class="badge-out-of-stock">Нет в наличии</span>
            @endif
        </div>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('products.show', $product->id) }}"
           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Подробнее
        </a>

        @if ($product->isInStock())
            <form method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn-primary px-3 py-2">
                    В корзину
                </button>
            </form>
        @else
            <button type="button" disabled
                    class="inline-flex cursor-not-allowed items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-medium text-gray-500">
                В корзину
            </button>
        @endif
    </div>
</article>
