<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="container mx-auto px-4">
        <nav class="flex items-center justify-between h-16">
            <a href="{{ route('catalog.index') }}" class="text-lg font-semibold text-gray-900 hover:text-gray-700 sm:text-xl">
                {{ config('app.name') }}
            </a>

            <ul class="flex items-center gap-4 sm:gap-6">
                <li>
                    <a href="{{ route('catalog.index') }}" class="text-gray-600 hover:text-gray-900">
                        Каталог
                    </a>
                </li>
                <li>
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center text-gray-600 hover:text-gray-900">
                        Корзина
                        @if ($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>
