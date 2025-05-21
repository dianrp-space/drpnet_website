<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Deposit Funds') }}
        </h2>
        
        <style>
            .payment-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 40px;
                width: 100%;
            }
            
            .payment-logo svg, .payment-logo img {
                max-height: 90%;
                max-width: 90%;
            }
            
            .payment-method-item {
                position: relative;
                transition: all 0.2s ease;
            }
            
            .payment-method-item.selected::after {
                content: '';
                position: absolute;
                top: -2px;
                right: -2px;
                width: 20px;
                height: 20px;
                background-color: rgb(99 102 241);
                border-radius: 50%;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'/%3E%3C/svg%3E");
                background-size: 70%;
                background-position: center;
                background-repeat: no-repeat;
                border: 2px solid white;
                z-index: 1;
            }
            
            .dark .payment-method-item.selected::after {
                border-color: rgb(31 41 55);
            }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Balance Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Current Balance</h3>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($balance->balance, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Deposit Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Deposit Form</h3>
                    
                    <form method="POST" action="{{ route('deposit.process') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <div class="flex items-center mt-1">
                                <span class="bg-gray-100 dark:bg-gray-700 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-500 dark:text-gray-400">Rp</span>
                                <x-text-input id="amount" name="amount" type="number" class="mt-0 block w-full rounded-l-none" value="{{ old('amount') }}" min="10000" required />
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Minimum deposit: Rp 10.000</p>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <x-input-label for="payment_method" :value="__('Payment Method')" class="mb-3" />
                            
                            <!-- Hidden input to store selected payment method -->
                            <input type="hidden" name="payment_method" id="payment_method_input" value="{{ old('payment_method', 'BCAVA') }}">
                            
                            <!-- Virtual Account Payment Methods -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transfer Bank (Virtual Account)</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                                    @foreach($paymentMethods['virtual_account'] as $key => $name)
                                        <div class="payment-method-item {{ old('payment_method') == $key ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30' : 'border-gray-200 dark:border-gray-700' }} cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="{{ $key }}">
                                            <div class="flex flex-col items-center">
                                                <div class="w-full h-10 flex items-center justify-center mb-2">
                                                    @if($key == 'BCAVA')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/banks/bca.png') }}" alt="BCA Virtual Account" class="h-8">
                                                        </div>
                                                    @elseif($key == 'BNIVA')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/banks/bni.png') }}" alt="BNI Virtual Account" class="h-8">
                                                        </div>
                                                    @elseif($key == 'BRIVA')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/banks/bri.png') }}" alt="BRI Virtual Account" class="h-8">
                                                        </div>
                                                    @elseif($key == 'MANDIRIVA')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/banks/mandiri.png') }}" alt="Mandiri Virtual Account" class="h-8">
                                                        </div>
                                                    @elseif($key == 'PERMATAVA')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/banks/permata.png') }}" alt="Permata Virtual Account" class="h-8">
                                                        </div>
                                                    @elseif($key == 'MYBVA')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/banks/maybank.png') }}" alt="Maybank Virtual Account" class="h-8">
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-center text-gray-700 dark:text-gray-300">{{ $name }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- E-Wallet Payment Methods -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-Wallet</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                                    @foreach($paymentMethods['ewallet'] as $key => $name)
                                        <div class="payment-method-item {{ old('payment_method') == $key ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30' : 'border-gray-200 dark:border-gray-700' }} cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="{{ $key }}">
                                            <div class="flex flex-col items-center">
                                                <div class="w-full h-10 flex items-center justify-center mb-2">
                                                    @if($key == 'QRIS')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/ewallet/qris.png') }}" alt="QRIS" class="h-8">
                                                        </div>
                                                    @elseif($key == 'OVO')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/ewallet/ovo.png') }}" alt="OVO" class="h-8">
                                                        </div>
                                                    @elseif($key == 'DANA')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/ewallet/dana.png') }}" alt="DANA" class="h-8">
                                                        </div>
                                                    @elseif($key == 'SHOPEEPAY')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/ewallet/shopeepay.png') }}" alt="ShopeePay" class="h-8">
                                                        </div>
                                                    @elseif($key == 'LINKAJA')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/ewallet/linkaja.png') }}" alt="LinkAja" class="h-8">
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-center text-gray-700 dark:text-gray-300">{{ $name }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Retail Payment Methods -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Retail</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($paymentMethods['retail'] as $key => $name)
                                        <div class="payment-method-item {{ old('payment_method') == $key ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30' : 'border-gray-200 dark:border-gray-700' }} cursor-pointer border rounded-lg p-3 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" data-method="{{ $key }}">
                                            <div class="flex flex-col items-center">
                                                <div class="w-full h-10 flex items-center justify-center mb-2">
                                                    @if($key == 'ALFAMART')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/retail/alfamart.png') }}" alt="Alfamart" class="h-8">
                                                        </div>
                                                    @elseif($key == 'INDOMARET')
                                                        <div class="payment-logo">
                                                            <img src="{{ asset('images/payment/retail/indomaret.png') }}" alt="Indomaret" class="h-8">
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-center text-gray-700 dark:text-gray-300">{{ $name }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- JavaScript untuk menangani pemilihan metode pembayaran -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const methodItems = document.querySelectorAll('.payment-method-item');
                                const hiddenInput = document.getElementById('payment_method_input');
                                
                                // Set default selected (first item)
                                if (!hiddenInput.value) {
                                    hiddenInput.value = methodItems[0].dataset.method;
                                    methodItems[0].classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30');
                                    methodItems[0].classList.add('selected');
                                }
                                
                                methodItems.forEach(item => {
                                    // Highlight selected method
                                    if (item.dataset.method === hiddenInput.value) {
                                        item.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30');
                                        item.classList.add('selected');
                                    }
                                    
                                    item.addEventListener('click', function() {
                                        // Remove highlight from all items
                                        methodItems.forEach(i => {
                                            i.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30', 'selected');
                                            i.classList.add('border-gray-200', 'dark:border-gray-700');
                                        });
                                        
                                        // Add highlight to selected item
                                        this.classList.remove('border-gray-200', 'dark:border-gray-700');
                                        this.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30', 'selected');
                                        
                                        // Update hidden input value
                                        hiddenInput.value = this.dataset.method;
                                    });
                                });
                            });
                        </script>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button type="button" onclick="window.history.back();" class="mr-4">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Continue to Payment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 