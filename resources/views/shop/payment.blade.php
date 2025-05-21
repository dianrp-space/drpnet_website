<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Complete Your Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg mb-6">
                        <div class="flex items-center">
                            <div class="mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-blue-800 dark:text-blue-300">Order Information</h3>
                                <p class="text-blue-700 dark:text-blue-400">Transaction ID: {{ $purchase->transaction_id }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Order Summary</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    @if($purchase->product->image)
                                        <img src="{{ Storage::url($purchase->product->image) }}" alt="{{ $purchase->product->name }}" class="w-12 h-12 object-cover rounded mr-3">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded mr-3 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-medium">{{ $purchase->product->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($purchase->product->description, 40) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold">Rp {{ number_format($purchase->price_paid, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2">
                                <div class="flex justify-between font-bold">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($purchase->price_paid, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('shop.process-payment', $purchase) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Payment Method
                            </label>
                            
                            <!-- Hidden input to store selected payment method -->
                            <input type="hidden" name="payment_method" id="payment_method_input" value="{{ old('payment_method', 'BCAVA') }}">
                            
                            <!-- Balance Payment Option -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Saldo</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="BALANCE">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-full mr-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-800 dark:text-gray-200">Bayar dengan Saldo</span>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Saldo Anda: Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div>
                                                @if(auth()->user()->balance >= $purchase->price_paid)
                                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 text-xs rounded-full">Saldo Cukup</span>
                                                @else
                                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 text-xs rounded-full">Saldo Tidak Cukup</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Virtual Account Payment Methods -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transfer Bank (Virtual Account)</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                                    <div class="payment-method-item border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="BCAVA">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/banks/bca.png') }}" alt="BCA Virtual Account" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">BCA Virtual Account</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="BNIVA">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/banks/bni.png') }}" alt="BNI Virtual Account" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">BNI Virtual Account</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="BRIVA">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/banks/bri.png') }}" alt="BRI Virtual Account" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">BRI Virtual Account</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="MANDIRIVA">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/banks/mandiri.png') }}" alt="Mandiri Virtual Account" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">Mandiri Virtual Account</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="PERMATAVA">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/banks/permata.png') }}" alt="Permata Virtual Account" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">Permata Virtual Account</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="MYBVA">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/banks/maybank.png') }}" alt="Maybank Virtual Account" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">Maybank Virtual Account</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- E-Wallet Payment Methods -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-Wallet</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="QRIS">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/ewallet/qris.png') }}" alt="QRIS" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">QRIS</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="OVO">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/ewallet/ovo.png') }}" alt="OVO" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">OVO</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="DANA">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/ewallet/dana.png') }}" alt="DANA" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">DANA</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="SHOPEEPAY">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/ewallet/shopeepay.png') }}" alt="ShopeePay" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">ShopeePay</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="LINKAJA">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/ewallet/linkaja.png') }}" alt="LinkAja" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">LinkAja</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Retail Payment Methods -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Retail</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="ALFAMART">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/retail/alfamart.png') }}" alt="Alfamart" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">Alfamart</span>
                                        </div>
                                    </div>
                                    <div class="payment-method-item border-gray-200 dark:border-gray-700 cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="INDOMARET">
                                        <div class="flex flex-col items-center">
                                            <div class="w-full h-10 flex items-center justify-center mb-2">
                                                <div class="payment-logo">
                                                    <img src="{{ asset('images/payment/retail/indomaret.png') }}" alt="Indomaret" class="h-8">
                                                </div>
                                            </div>
                                            <span class="text-xs text-center text-gray-700 dark:text-gray-300">Indomaret</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <strong>Note:</strong> This is a simulation. In a real application, this would connect to the Tripay payment gateway.
                            </p>
                        </div>
                        
                        <div>
                            <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg">
                                Complete Payment
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6">
                        <a href="{{ route('shop.show', $purchase->product) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                            &larr; Back to Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethodItems = document.querySelectorAll('.payment-method-item');
            const paymentMethodInput = document.getElementById('payment_method_input');
            
            // Set initial active state
            const initialMethod = paymentMethodInput.value;
            paymentMethodItems.forEach(item => {
                if (item.dataset.method === initialMethod) {
                    item.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30');
                    item.classList.remove('border-gray-200', 'dark:border-gray-700');
                }
            });
            
            // Add click event listener to all payment method items
            paymentMethodItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active state from all items
                    paymentMethodItems.forEach(i => {
                        i.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30');
                        i.classList.add('border-gray-200', 'dark:border-gray-700');
                    });
                    
                    // Add active state to clicked item
                    this.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30');
                    this.classList.remove('border-gray-200', 'dark:border-gray-700');
                    
                    // Update hidden input
                    paymentMethodInput.value = this.dataset.method;
                });
            });
        });
    </script>
</x-app-layout> 