<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transfer Details') }}
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
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Transfer #{{ $transfer->id }}</h3>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transfer Date</p>
                                <p class="mt-1 text-base font-medium text-gray-900 dark:text-gray-100">{{ $transfer->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <div class="mt-1">
                                    @if ($transfer->status == 'success')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Success
                                        </span>
                                    @elseif ($transfer->status == 'pending')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            Pending
                                        </span>
                                    @elseif ($transfer->status == 'failed')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            Failed
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
                                <p class="mt-1 text-xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($transfer->amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Sender Information -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Sender</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Name</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100 mb-3">{{ $transfer->fromUser->name }}</p>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $transfer->fromUser->email }}</p>
                        </div>
                        
                        <!-- Recipient Information -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Recipient</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Name</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100 mb-3">{{ $transfer->toUser->name }}</p>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $transfer->toUser->email }}</p>
                        </div>
                    </div>
                    
                    @if($transfer->description)
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Description</h4>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-700 dark:text-gray-300">{{ $transfer->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 