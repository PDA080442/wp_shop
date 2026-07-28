<tr @class(['bg-amber-50/50' => $item->exceedsStock()])>
    <td class="px-2 py-4 sm:px-4">
        <a href="{{ route('products.show', $item->productId) }}"
           class="font-medium text-gray-900 hover:text-gray-700">
            {{ $item->name }}
        </a>
        @if ($item->exceedsStock())
            <div class="mt-2 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                @if ($item->stock === 0)
                    {{ __('cart.out_of_stock_in_cart', ['name' => $item->name]) }}
                @else
                    {{ __('cart.stock_warning', ['stock' => $item->stock, 'name' => $item->name]) }}
                @endif
            </div>
        @endif
    </td>
    <td class="whitespace-nowrap px-2 py-4 text-gray-900 sm:px-4">
        {{ $item->formattedPrice() }}
    </td>
    <td class="px-2 py-4 sm:px-4">
        <form method="POST" action="{{ route('cart.update', $item->productId) }}"
              class="flex flex-wrap items-center gap-2">
            @csrf
            @method('PATCH')
            <input type="number"
                   name="quantity"
                   value="{{ old('quantity', $item->quantity) }}"
                   min="1"
                   max="{{ max($item->stock, 1) }}"
                   class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('quantity') input-error @enderror">
            @error('quantity')
                <p class="mt-1 w-full text-sm text-red-600">{{ $message }}</p>
            @enderror
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                {{ __('cart.update') }}
            </button>
        </form>
    </td>
    <td class="whitespace-nowrap px-2 py-4 font-medium text-gray-900 sm:px-4">
        {{ $item->formattedSubtotal() }}
    </td>
    <td class="px-2 py-4 sm:px-4">
        <form method="POST" action="{{ route('cart.remove', $item->productId) }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center rounded-md border border-red-200 bg-white px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-50">
                {{ __('cart.remove') }}
            </button>
        </form>
    </td>
</tr>
