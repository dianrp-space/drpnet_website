<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shop') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($products->isEmpty())
                        <p class="text-center py-8">No products available at this time.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:scale-105">
                                    @if ($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover object-center">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-gray-400 dark:text-gray-300">No image</span>
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">{{ $product->name }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                            {{ Str::limit($product->description, 100) }}
                                        </p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                            <div class="flex space-x-2">
                                                @auth
                                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="w-10 h-10 flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white rounded">
                                                            <span class="text-xl">🛒</span>
                                                        </button>
                                                    </form>
                                                @endauth
                                                <a href="{{ route('shop.show', $product) }}" class="w-10 h-10 flex items-center justify-center bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded">
                                                    <span class="text-xl">🔍</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 