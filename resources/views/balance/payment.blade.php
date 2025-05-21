<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment Details') }}
        </h2>
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

            <!-- Payment Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Transaction Details</h3>
                    
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Transaction ID</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $transaction->id }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Date</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Amount</p>
                            <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Payment Method</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Logo</p>
                            <div class="payment-logo mt-1" style="height: 30px; width: auto; justify-content: flex-start;">
                                @php
                                    $logoPath = '';
                                    $paymentMethod = $transaction->payment_method;
                                    if (in_array($paymentMethod, ['MYBVA', 'PERMATAVA', 'BRIVA', 'MANDIRIVA', 'BCAVA', 'BNIVA'])) {
                                        $logoPath = 'images/payment/banks/' . strtolower(str_replace('VA', '', $paymentMethod)) . '.png';
                                    } elseif (in_array($paymentMethod, ['QRIS', 'OVO', 'DANA', 'SHOPEEPAY', 'LINKAJA'])) {
                                        $logoPath = 'images/payment/ewallet/' . strtolower($paymentMethod) . '.png';
                                    } elseif (in_array($paymentMethod, ['ALFAMART', 'INDOMARET'])) {
                                        $logoPath = 'images/payment/retail/' . strtolower($paymentMethod) . '.png';
                                    }
                                @endphp
                                @if($logoPath)
                                    <img src="{{ asset($logoPath) }}" alt="{{ $transaction->payment_method }} logo" class="h-full">
                                @else
                                    <span>No logo</span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Description</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $transaction->description ?: 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- In a real app, this would display payment instructions from Tripay -->
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Payment Instructions</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>This is a simulation. In a real application, payment instructions from Tripay would be displayed here.</p>
                                    <p class="mt-1">For testing purposes, please use the buttons below to complete or cancel the payment.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                        <form method="POST" action="{{ route('deposit.complete', $transaction->id) }}">
                            @csrf
                            <x-primary-button>
                                {{ __('Complete Payment') }}
                            </x-primary-button>
                        </form>
                        
                        <form method="POST" action="{{ route('deposit.cancel', $transaction->id) }}">
                            @csrf
                            <x-secondary-button type="submit">
                                {{ __('Cancel Payment') }}
                            </x-secondary-button>
                        </form>
                        
                        <a href="{{ route('balance.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Back to Balance') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 