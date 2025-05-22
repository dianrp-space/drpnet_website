<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Site Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Display success message if available -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Appearance Settings Section -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Appearance Settings') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Customize your site\'s appearance.') }}
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Logo -->
                                <div>
                                    <x-input-label for="site_logo" :value="__('Site Logo')" />
                                    
                                    @if (isset($settings['site_logo']))
                                        <div class="mt-2 mb-4">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Current logo:</p>
                                            <img src="{{ asset('storage/' . $settings['site_logo']->value) }}" alt="Site Logo" class="max-h-32">
                                        </div>
                                    @endif
                                    
                                    <input type="file" id="site_logo" name="site_logo" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-indigo-900 dark:file:text-indigo-300
                                        dark:hover:file:bg-indigo-800">
                                    
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Recommended size: 200x50 pixels. PNG or SVG format.
                                    </p>
                                </div>
                                
                                <!-- Favicon -->
                                <div>
                                    <x-input-label for="site_favicon" :value="__('Site Favicon')" />
                                    
                                    @if (isset($settings['site_favicon']))
                                        <div class="mt-2 mb-4">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Current favicon:</p>
                                            <img src="{{ asset('storage/' . $settings['site_favicon']->value) }}" alt="Site Favicon" class="max-h-16">
                                        </div>
                                    @endif
                                    
                                    <input type="file" id="site_favicon" name="site_favicon" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-indigo-900 dark:file:text-indigo-300
                                        dark:hover:file:bg-indigo-800">
                                    
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Recommended size: 32x32 pixels or 16x16 pixels. ICO, PNG, or SVG format.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Home Page Settings -->
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Homepage Settings') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Configure your homepage appearance.') }}
                                </p>
                                
                                <div class="mt-4">
                                    <a href="{{ route('admin.settings.slides') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Manage Welcome Slider Images
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Shop Status Settings -->
                            <div class="mt-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('messages.shop_status') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('messages.shop_status_description') }}
                                </p>
                                <div class="mt-4">
                                    <label for="shop_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.shop_status') }}</label>
                                    <select id="shop_status" name="shop_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="open" {{ (isset($settings['shop_status']) && $settings['shop_status']->value == 'open') || !isset($settings['shop_status']) ? 'selected' : '' }}>{{ __('messages.shop_status_open') }}</option>
                                        <option value="closed" {{ isset($settings['shop_status']) && $settings['shop_status']->value == 'closed' ? 'selected' : '' }}>{{ __('messages.shop_status_closed') }}</option>
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('messages.shop_status_help') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Payment Gateway Settings -->
                            <div class="mt-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Tripay Payment Gateway') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Configure your Tripay payment gateway settings. These credentials are required for processing payments.') }}
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <!-- Tripay Merchant Code -->
                                    <div>
                                        <x-input-label for="tripay_merchant_code" :value="__('Merchant Code')" />
                                        <x-text-input id="tripay_merchant_code" name="tripay_merchant_code" type="text" class="mt-1 block w-full" 
                                            value="{{ $settings['tripay_merchant_code']->value ?? '' }}" />
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Your Tripay merchant code (from Tripay dashboard).
                                        </p>
                                    </div>
                                    
                                    <!-- Tripay API Key -->
                                    <div>
                                        <x-input-label for="tripay_api_key" :value="__('API Key')" />
                                        <x-text-input id="tripay_api_key" name="tripay_api_key" type="password" class="mt-1 block w-full" 
                                            value="{{ $settings['tripay_api_key']->value ?? '' }}" />
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Your Tripay API key (from Tripay dashboard).
                                        </p>
                                    </div>
                                    
                                    <!-- Tripay Private Key -->
                                    <div>
                                        <x-input-label for="tripay_private_key" :value="__('Private Key')" />
                                        <x-text-input id="tripay_private_key" name="tripay_private_key" type="password" class="mt-1 block w-full" 
                                            value="{{ $settings['tripay_private_key']->value ?? '' }}" />
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Your Tripay private key (from Tripay dashboard).
                                        </p>
                                    </div>
                                    
                                    <!-- Tripay Sandbox Mode -->
                                    <div>
                                        <div class="flex items-center mt-4">
                                            <input type="checkbox" id="tripay_sandbox" name="tripay_sandbox" value="1" 
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                                {{ isset($settings['tripay_sandbox']) && $settings['tripay_sandbox']->value == "1" ? 'checked' : '' }}>
                                            <label for="tripay_sandbox" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable Sandbox Mode</label>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Enable for testing. Disable in production.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- WhatsApp Gateway Settings -->
                            <div class="mt-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('WhatsApp Notification Gateway') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Configure WhatsApp API for sending notifications to users.') }}
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <!-- WhatsApp API URL -->
                                    <div>
                                        <x-input-label for="whatsapp_api_url" :value="__('API URL')" />
                                        <x-text-input id="whatsapp_api_url" name="whatsapp_api_url" type="text" class="mt-1 block w-full" 
                                            value="{{ $settings['whatsapp_api_url']->value ?? '' }}" />
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('The endpoint URL for your WhatsApp API provider.') }}
                                        </p>
                                    </div>
                                    
                                    <!-- WhatsApp API Key/Token -->
                                    <div>
                                        <x-input-label for="whatsapp_api_token" :value="__('API Token')" />
                                        <x-text-input id="whatsapp_api_token" name="whatsapp_api_token" type="password" class="mt-1 block w-full" 
                                            value="{{ $settings['whatsapp_api_token']->value ?? '' }}" />
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Your API token or key for authentication.') }}
                                        </p>
                                    </div>
                                    
                                    <!-- WhatsApp Sender ID -->
                                    <div>
                                        <x-input-label for="whatsapp_sender" :value="__('Sender ID/Number')" />
                                        <x-text-input id="whatsapp_sender" name="whatsapp_sender" type="text" class="mt-1 block w-full" 
                                            value="{{ $settings['whatsapp_sender']->value ?? '' }}" placeholder="Example: 628123456789" />
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('The WhatsApp number you use to send messages (with country code, no plus sign).') }}
                                        </p>
                                    </div>
                                    
                                    <!-- Enable WhatsApp Notifications -->
                                    <div>
                                        <div class="flex items-center mt-4">
                                            <input type="checkbox" id="whatsapp_enabled" name="whatsapp_enabled" value="1" 
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                                {{ isset($settings['whatsapp_enabled']) && $settings['whatsapp_enabled']->value == "1" ? 'checked' : '' }}>
                                            <label for="whatsapp_enabled" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable WhatsApp Notifications</label>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Enable to send WhatsApp notifications for transactions and orders.') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('admin.settings.whatsapp-test') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Test WhatsApp Integration
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Save Settings') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 