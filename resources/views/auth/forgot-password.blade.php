<x-guest-layout>
    <div x-data="{ sendMethod: 'email' }" class="space-y-4">
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your password? No problem. Choose your preferred method to receive a password reset link.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Send Method Choice -->
            <div>
                <x-input-label :value="__('Choose Reset Method')" />
                <div class="mt-2 space-y-2 sm:space-y-0 sm:flex sm:space-x-4">
                    <label for="send_via_email" class="flex items-center">
                        <input id="send_via_email" type="radio" name="send_method_choice" value="email" x-model="sendMethod" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Via Email') }}</span>
                    </label>
                    <label for="send_via_whatsapp" class="flex items-center">
                        <input id="send_via_whatsapp" type="radio" name="send_method_choice" value="whatsapp" x-model="sendMethod" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Via WhatsApp') }}</span>
                    </label>
                </div>
            </div>
            
            <input type="hidden" name="send_method" x-bind:value="sendMethod">


            <!-- Email Address -->
            <div x-show="sendMethod === 'email'" x-transition>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" x-bind:required="sendMethod === 'email'" autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- WhatsApp Number -->
            <div x-show="sendMethod === 'whatsapp'" x-transition>
                <x-input-label for="whatsapp_number" :value="__('WhatsApp Number')" />
                <x-text-input id="whatsapp_number" class="block mt-1 w-full" type="text" name="whatsapp_number" :value="old('whatsapp_number')" x-bind:required="sendMethod === 'whatsapp'" placeholder="{{ __('e.g., 6281234567890') }}" />
                <x-input-error :messages="$errors->get('whatsapp_number')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Please include your country code.') }}</p>
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button>
                    {{ __('Send Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
