<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pembayaran Berhasil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Notifikasi Sukses -->
                    <div class="text-center mb-8">
                        <div class="mx-auto w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">Pembayaran Berhasil!</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Terima kasih atas pembelian Anda. Produk digital kini siap untuk diunduh.</p>
                        <div class="text-sm bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg inline-block">
                            ID Transaksi: <span class="font-mono font-medium">{{ $purchase->transaction_id }}</span>
                        </div>
                    </div>
                    
                    <!-- Detail Produk -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold mb-3">Detail Pembelian</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center">
                                @if($purchase->product->image_path)
                                    <img src="{{ Storage::url($purchase->product->image_path) }}" alt="{{ $purchase->product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded mr-4 flex items-center justify-center">
                                        <span class="text-gray-400 dark:text-gray-300">No image</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h5 class="font-medium text-lg">{{ $purchase->product->name }}</h5>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($purchase->product->description, 100) }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-blue-600 dark:text-blue-400">
                                        Rp {{ number_format($purchase->price_paid, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        via {{ ucfirst($purchase->payment_method) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Opsi untuk User -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-center space-y-4 md:space-y-0 md:space-x-4">
                        <a href="{{ route('shop.download', $purchase->product) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-center transition flex-1 md:flex-initial flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Sekarang
                        </a>
                        <a href="{{ route('shop.my-purchases') }}" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-3 px-6 rounded-lg text-center transition flex-1 md:flex-initial flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Lihat Semua Pembelian
                        </a>
                        <a href="{{ route('shop.index') }}" class="border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-3 px-6 rounded-lg text-center transition flex-1 md:flex-initial flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Kembali ke Toko
                        </a>
                    </div>
                    
                    <!-- Informasi Tambahan -->
                    <div class="mt-8 text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="mb-2"><strong>Informasi Penting:</strong></p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Produk digital yang telah dibeli dapat diunduh kapan saja dari halaman "Pembelian Saya".</li>
                            <li>Jika Anda memerlukan bantuan, silakan hubungi dukungan pelanggan kami.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 