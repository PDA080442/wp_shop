<div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
                    {{ __('checkout.item_name') }}
                </th>
                <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
                    {{ __('checkout.item_quantity') }}
                </th>
                <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
                    {{ __('checkout.item_price') }}
                </th>
                <th scope="col" class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:px-4">
                    {{ __('checkout.item_subtotal') }}
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            @foreach ($order->items as $item)
                <tr>
                    <td class="px-2 py-3 text-sm text-gray-900 sm:px-4">{{ $item->product_name }}</td>
                    <td class="px-2 py-3 text-sm text-gray-700 sm:px-4">{{ $item->quantity }}</td>
                    <td class="whitespace-nowrap px-2 py-3 text-sm text-gray-700 sm:px-4">{{ $item->formattedPrice() }}</td>
                    <td class="whitespace-nowrap px-2 py-3 text-sm font-medium text-gray-900 sm:px-4">{{ $item->formattedSubtotal() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
