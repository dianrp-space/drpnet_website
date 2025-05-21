<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Test WhatsApp Integration') }}
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if ($enabled != '1')
                        <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Warning!</strong>
                            <span class="block sm:inline">WhatsApp notifications are currently disabled in your settings. Tests will still work but live notifications will not be sent.</span>
                            <a href="{{ route('admin.settings.index') }}" class="underline text-blue-700">Go to settings</a> to enable WhatsApp notifications.
                        </div>
                    @endif
                    
                    @if (empty($apiToken) || empty($sender))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">WhatsApp API is not properly configured. Please set up your API credentials in the settings.</span>
                            <a href="{{ route('admin.settings.index') }}" class="underline text-blue-700">Go to settings</a> to configure WhatsApp API.
                        </div>
                    @endif
                    
                    <h3 class="text-lg font-medium mb-4">Current Configuration</h3>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-md p-4 mb-6">
                        <p><strong>API URL:</strong> {{ $apiUrl }}</p>
                        <p><strong>API Token:</strong> {{ !empty($apiToken) ? '••••••' . substr($apiToken, -4) : 'Not set' }}</p>
                        <p><strong>Sender:</strong> {{ $sender ?: 'Not set' }}</p>
                        <p><strong>Status:</strong> {{ $enabled == '1' ? 'Enabled' : 'Disabled' }}</p>
                    </div>
                    
                    <h3 class="text-lg font-medium mb-4">Send Test Message</h3>
                    <form action="{{ route('admin.settings.whatsapp-test.send') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="recipient" :value="__('Recipient Phone Number')" />
                            <x-text-input id="recipient" name="recipient" type="text" class="mt-1 block w-full" 
                                value="{{ old('recipient') }}" placeholder="628123456789" required autofocus />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Enter the phone number with country code, without + sign (e.g., 628123456789)
                            </p>
                            @error('recipient')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <x-input-label for="message" :value="__('Message')" />
                            <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>{{ old('message', 'This is a test message from DRP Net application.') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <x-input-label for="footer" :value="__('Footer')" />
                            <x-text-input id="footer" name="footer" type="text" class="mt-1 block w-full" 
                                value="{{ old('footer', 'Sent from DRP Net Admin Panel') }}" />
                            @error('footer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <x-primary-button>
                                {{ __('Send Test Message') }}
                            </x-primary-button>
                            
                            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Back to Settings
                            </a>
                        </div>
                    </form>
                    
                    <div class="mt-8 bg-gray-100 dark:bg-gray-700 rounded-md p-4">
                        <h3 class="text-lg font-medium mb-2">API Documentation</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Parameter</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Type</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Required</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">api_key</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">string</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Yes</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">API Key</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">sender</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">string</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Yes</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Number of your device</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">number</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">string</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Yes</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Recipient number ex 72888xxxx|62888xxxx</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">message</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">string</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Yes</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Message to be sent</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">footer</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">string</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Yes</td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Footer under message</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-4 text-sm">
                            <strong>Endpoint:</strong> https://wa.drpnet.my.id/send-message<br>
                            <strong>Method:</strong> POST | GET
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 