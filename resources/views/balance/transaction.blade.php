<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Transaction #{{ $transaction->id }}</h3>
                        
                        @if($transaction->status == 'pending' && $transaction->type == 'deposit')
                            <div class="mt-2 md:mt-0 space-x-2">
                                <form method="POST" action="{{ route('deposit.complete', $transaction->id) }}" class="inline-block">
                                    @csrf
                                    <x-primary-button>
                                        {{ __('Complete Payment') }}
                                    </x-primary-button>
                                </form>
                                
                                <form method="POST" action="{{ route('deposit.cancel', $transaction->id) }}" class="inline-block">
                                    @csrf
                                    <x-secondary-button type="submit">
                                        {{ __('Cancel Payment') }}
                                    </x-secondary-button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transaction Type</p>
                                <div class="mt-1">
                                    @if ($transaction->type == 'deposit')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Deposit
                                        </span>
                                    @elseif ($transaction->type == 'purchase')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                                            Purchase
                                        </span>
                                    @elseif ($transaction->type == 'transfer')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            Transfer
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                                <p class="mt-1 text-base font-medium text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <div class="mt-1">
                                    @if ($transaction->status == 'success')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Success
                                        </span>
                                    @elseif ($transaction->status == 'pending')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            Pending
                                        </span>
                                    @elseif ($transaction->status == 'failed')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            Failed
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
                                <p class="mt-1 text-xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                            </div>
                            
                            @if($transaction->payment_method)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Payment Method</p>
                                <p class="mt-1 text-base font-medium text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</p>
                            </div>
                            @endif
                            
                            @if($transaction->reference)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Reference</p>
                                <p class="mt-1 text-base font-medium text-gray-900 dark:text-gray-100">{{ $transaction->reference }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($transaction->description)
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Description</h4>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-700 dark:text-gray-300">{{ $transaction->description }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($transaction->type == 'transfer' && $transaction->related_id)
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Transfer Details</h4>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                            <a href="{{ route('transfer.details', $transaction->related_id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                View Transfer Details
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 