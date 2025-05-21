<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                    <div class="mb-4">
                        <span class="text-lg font-semibold">Harga: </span>
                        <span class="text-xl text-green-700 font-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="text-sm text-gray-500">Status: </span>
                        @if($product->is_active)
                            <span class="text-green-600 font-semibold">Aktif</span>
                        @else
                            <span class="text-red-600 font-semibold">Nonaktif</span>
                        @endif
                    </div>
                    @if($product->image_path)
                        <div class="mb-6">
                            <img src="{{ Storage::url($product->image_path) }}" alt="Gambar Produk" class="w-full max-w-xs rounded shadow">
                        </div>
                    @endif
                    <div class="mt-6">
                        <form action="#" method="POST">
                            @csrf
                            <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline" disabled>
                                Beli Sekarang (Coming Soon)
                            </button>
                        </form>
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali ke Daftar Produk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 