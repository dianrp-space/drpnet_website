<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Balance') }}
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

            <!-- Balance Summary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Current Balance -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-6 text-white shadow-lg">
                            <h3 class="text-lg font-semibold mb-2">Current Balance</h3>
                            <p class="text-3xl font-bold">Rp {{ number_format($balance->balance, 0, ',', '.') }}</p>
                            <div class="mt-4 flex space-x-2">
                                <a href="{{ route('deposit.form') }}" class="inline-flex items-center px-3 py-2 bg-white text-indigo-700 rounded-md text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add Funds
                                </a>
                                <a href="{{ route('transfer.form') }}" class="inline-flex items-center px-3 py-2 border border-white text-white rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Transfer
                                </a>
                            </div>
                        </div>

                        <!-- Deposits Summary -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow border border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Total Deposits</h3>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                Rp {{ number_format(
                                    $transactions->where('type', 'deposit')->where('status', 'success')->sum('amount'), 
                                    0, ',', '.'
                                ) }}
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('balance.history') }}?type=deposit" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                                    View Deposit History →
                                </a>
                            </div>
                        </div>

                        <!-- Transfers/Purchases Summary -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow border border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Total Spent</h3>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                Rp {{ number_format(
                                    $transactions->whereIn('type', ['purchase', 'transfer'])->where('status', 'success')->sum('amount'), 
                                    0, ',', '.'
                                ) }}
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('shop.my-purchases') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                                    View Purchase History →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Transactions</h3>
                        <a href="{{ route('balance.history') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                            View All
                        </a>
                    </div>

                    @if ($transactions->isEmpty())
                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                            <p>No transactions found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transaction->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if ($transaction->type == 'deposit')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        Deposit
                                                    </span>
                                                @elseif ($transaction->type == 'purchase')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                                                        Purchase
                                                    </span>
                                                @elseif ($transaction->type == 'transfer')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                        Transfer
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if ($transaction->status == 'success')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        Success
                                                    </span>
                                                @elseif ($transaction->status == 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                        Pending
                                                    </span>
                                                @elseif ($transaction->status == 'failed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                        Failed
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transaction->description ?: 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 