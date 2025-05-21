<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-xl font-bold">{{ __('Keranjang Belanja') }}</h1>
                        <div class="flex space-x-2">
                            <a href="{{ route('shop.index') }}" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded transition">
                                <i class="fas fa-shopping-bag mr-1"></i> {{ __('Lanjut Belanja') }}
                            </a>
                            @if ($cartItems->count() > 0)
                                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('{{ __('Anda yakin ingin mengosongkan keranjang?') }}')">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded transition">
                                        <i class="fas fa-trash mr-1"></i> {{ __('Kosongkan Keranjang') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    @if ($cartItems->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-5xl mb-4">
                                <i class="fas fa-shopping-cart text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">{{ __('Keranjang belanja Anda kosong.') }}</p>
                            <a href="{{ route('shop.index') }}" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-6 py-2 rounded transition">
                                {{ __('Mulai Belanja') }}
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                                <thead class="bg-gray-100 dark:bg-gray-600">
                                    <tr>
                                        <th class="py-3 px-4 text-left">{{ __('Produk') }}</th>
                                        <th class="py-3 px-4 text-center">{{ __('Harga') }}</th>
                                        <th class="py-3 px-4 text-center">{{ __('Subtotal') }}</th>
                                        <th class="py-3 px-4 text-center">{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach ($cartItems as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center">
                                                    @if ($item->product->image_path)
                                                        <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                                    @else
                                                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded mr-4 flex items-center justify-center">
                                                            <span class="text-gray-400 dark:text-gray-300">No image</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <a href="{{ route('shop.show', $item->product) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                                            {{ $item->product->name }}
                                                        </a>
                                                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Produk Digital') }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="py-4 px-4 text-center font-semibold">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-500 hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800 text-white rounded">
                                                        <span class="text-xl">🗑️</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-600">
                                    <tr>
                                        <td colspan="2" class="py-4 px-4 text-right font-bold">
                                            {{ __('Total') }}:
                                        </td>
                                        <td class="py-4 px-4 text-center font-bold">
                                            Rp {{ number_format($cart->total_price, 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 dark:bg-green-700 dark:hover:bg-green-800 text-white px-6 py-3 rounded-lg text-lg font-semibold transition">
                                    <i class="fas fa-credit-card mr-2"></i> {{ __('Checkout') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function incrementQuantity(button) {
            const input = button.parentNode.querySelector('input');
            input.value = parseInt(input.value) + 1;
        }
        
        function decrementQuantity(button) {
            const input = button.parentNode.querySelector('input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
    @endpush
</x-app-layout> 