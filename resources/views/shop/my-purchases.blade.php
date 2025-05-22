<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Purchases & Deposits') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Product Purchase History') }}</h3>
                    @if ($purchases->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-lg mb-4 text-gray-700 dark:text-gray-300">You haven't purchased any products yet.</p>
                            <a href="{{ route('shop.index') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded">
                                Browse Products
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Product</th>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Price Paid</th>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Purchase Date</th>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Payment Status</th>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($purchases as $purchase)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                                <div class="flex items-center">
                                                    @if ($purchase->product->image_path)
                                                        <img src="{{ Storage::url($purchase->product->image_path) }}" alt="{{ $purchase->product->name }}" class="w-12 h-12 rounded object-cover mr-4">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center mr-4">
                                                            <span class="text-gray-400 dark:text-gray-300 text-xs">No image</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <a href="{{ route('shop.show', $purchase->product) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                                            {{ $purchase->product->name }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                                Rp {{ number_format($purchase->price_paid, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                                {{ $purchase->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($purchase->payment_status === 'completed')
                                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-500/20 text-green-800 dark:text-green-300 rounded-full text-xs font-medium">
                                                        Completed
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-800 dark:text-yellow-300 rounded-full text-xs font-medium">
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($purchase->payment_status === 'completed')
                                                    <a href="{{ route('shop.download', $purchase->product) }}" class="px-3 py-1 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white text-sm rounded">
                                                        Download
                                                    </a>
                                                @else
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('shop.payment', $purchase) }}" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white text-sm rounded">
                                                            Complete Payment
                                                        </a>
                                                        <form action="{{ route('shop.cancel-payment', $purchase) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan pembelian ini?');">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white text-sm rounded">
                                                                Batalkan
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            {{ $purchases->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Deposit History') }}</h3>
                    @if ($depositTransactions->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-lg mb-4 text-gray-700 dark:text-gray-300">You haven't made any deposits yet.</p>
                            <a href="{{ route('balance.deposit') }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white rounded">
                                Make a Deposit
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Date</th>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Description</th>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Amount</th>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Payment Method</th>
                                        <th class="py-3 px-4 text-left text-gray-600 dark:text-gray-300">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($depositTransactions as $transaction)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                                {{ $transaction->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                                {{ $transaction->description }}
                                            </td>
                                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                                {{ $transaction->payment_method ? Str::upper(str_replace('_', ' ', $transaction->payment_method)) : 'N/A' }}
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($transaction->status === 'success')
                                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-500/20 text-green-800 dark:text-green-300 rounded-full text-xs font-medium">
                                                        Success
                                                    </span>
                                                @elseif($transaction->status === 'pending')
                                                    <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-800 dark:text-yellow-300 rounded-full text-xs font-medium">
                                                        Pending
                                                    </span>
                                                @elseif($transaction->status === 'failed')
                                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-300 rounded-full text-xs font-medium">
                                                        Failed
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-500/20 text-gray-800 dark:text-gray-300 rounded-full text-xs font-medium">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            {{ $depositTransactions->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 