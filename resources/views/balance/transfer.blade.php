<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transfer Funds') }}
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

            <!-- Balance Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Current Balance</h3>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($balance->balance, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Transfer Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Transfer Form</h3>
                    
                    <form method="POST" action="{{ route('transfer.process') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="recipient_email" :value="__('Recipient Email')" />
                            <x-text-input id="recipient_email" name="recipient_email" type="email" class="mt-1 block w-full" value="{{ old('recipient_email') }}" required />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter the email address of the recipient</p>
                            @error('recipient_email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <div class="flex items-center mt-1">
                                <span class="bg-gray-100 dark:bg-gray-700 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-500 dark:text-gray-400">Rp</span>
                                <x-text-input id="amount" name="amount" type="number" class="mt-0 block w-full rounded-l-none" value="{{ old('amount') }}" min="1000" required />
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Minimum transfer: Rp 1.000</p>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Description (Optional)')" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" value="{{ old('description') }}" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a note for this transfer</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center">
                            <x-primary-button>
                                {{ __('Transfer Funds') }}
                            </x-primary-button>

                            <a href="{{ route('balance.index') }}" class="inline-flex items-center px-4 py-2 ml-4 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 