<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Complete Your Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <p class="text-lg font-medium">{{ __('Welcome') }}, {{ $user->name }}!</p>
                        <p class="mt-2">{{ __('To continue, please complete your profile information.') }}</p>
                    </div>

                    <form method="POST" action="{{ route('complete-profile.update') }}" class="mt-6 space-y-6">
                        @csrf

                        @if(empty($user->username))
                        <!-- Username -->
                        <div>
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autofocus autocomplete="username" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Choose a unique username for login. Only letters, numbers, dashes, and underscores allowed.') }}
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('username')" />
                        </div>
                        @endif

                        <!-- WhatsApp Number -->
                        <div>
                            <x-input-label for="phone" :value="__('WhatsApp Number')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autocomplete="tel" placeholder="628123456789" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Please include your country code, e.g., 6281234567890') }}
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save and Continue') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 