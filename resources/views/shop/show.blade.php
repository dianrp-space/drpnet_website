<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="md:w-1/2">
                            @if ($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="md:w-1/2">
                            <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>
                            
                            <div class="text-2xl font-bold text-blue-600 mb-6">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                            
                            <div class="prose mb-6">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                            
                            @if(Auth::check())
                                @if(isset($purchased) && $purchased)
                                    <div class="flex flex-col space-y-3">
                                        <a href="{{ route('shop.download', $product) }}" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors inline-flex items-center justify-center">
                                            <span class="mr-2">📥</span> Download Product
                                        </a>
                                        
                                        <form action="{{ route('cart.add', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center">
                                                <span class="text-xl mr-2">🛒</span> Tambahkan ke Keranjang
                                            </button>
                                        </form>
                                    </div>
                                @elseif(isset($pendingPayment) && $pendingPayment)
                                    <div class="flex flex-col space-y-3">
                                        <a href="{{ route('shop.payment', App\Models\Purchase::where('user_id', Auth::id())->where('product_id', $product->id)->first()) }}" class="px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition-colors inline-flex items-center justify-center">
                                            <span class="mr-2">💳</span> Complete Payment
                                        </a>
                                        
                                        <p class="mt-2 text-sm text-yellow-600">Your purchase is pending payment completion.</p>
                                    </div>
                                @else
                                    <div class="flex space-x-3">
                                        <form action="{{ route('shop.purchase', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                                <span class="mr-2">💰</span> Purchase Now
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('cart.add', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                                                <span class="text-xl mr-2">🛒</span> Tambahkan ke Keranjang
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                                    <span class="mr-2">🔑</span> Login to Purchase
                                </a>
                            @endif
                            
                            <div class="mt-4 text-sm text-gray-500">
                                <p>Ini adalah produk digital. Setelah pembelian dan pembayaran, Anda akan mendapatkan akses langsung untuk mengunduh file.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 flex justify-between">
                <a href="{{ route('shop.index') }}" class="text-blue-600 hover:underline flex items-center">
                    <span class="mr-1">⬅️</span> Kembali ke Toko
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 