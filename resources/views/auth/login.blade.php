<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Or log in with') }}:</p>
            <a href="{{ route('auth.google') }}" class="inline-flex items-center justify-center px-4 py-2 mt-2 border border-gray-300 dark:border-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 w-full">
                <!-- You can add a Google icon here if you have one -->
                <svg class="w-5 h-5 mr-2 -ml-1" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M47.5229 24.5452C47.5229 22.8362 47.3679 21.1272 47.0579 19.4907H24.2429V28.7271H37.3719C36.7869 31.7452 35.0169 34.2362 32.5229 35.8726V41.6362H40.0169C44.7119 37.3635 47.5229 31.4544 47.5229 24.5452Z" fill="#4285F4"/><path fill-rule="evenodd" clip-rule="evenodd" d="M24.2428 48.0001C30.6958 48.0001 36.0818 45.9274 40.0168 42.6365L32.5228 35.8728C30.3458 37.2819 27.5008 38.1819 24.2428 38.1819C17.8528 38.1819 12.2908 33.891 10.3768 28.0001L2.72726 32.7274C6.60001 40.5274 14.8364 48.0001 24.2428 48.0001Z" fill="#34A853"/><path fill-rule="evenodd" clip-rule="evenodd" d="M10.3767 28.0001C9.91818 26.6001 9.64091 25.1183 9.64091 23.5728C9.64091 22.0274 9.91818 20.5455 10.3591 19.1455V13.3819L2.72726 18.1092C1.00001 21.5183 0 25.5365 0 30.0001C0 34.4637 1.00001 38.4819 2.72726 41.891L10.3767 28.0001Z" fill="#FBBC05"/><path fill-rule="evenodd" clip-rule="evenodd" d="M24.2428 9.81812C27.7908 9.81812 30.9048 11.0045 33.3728 13.2009L40.1818 6.54541C36.0638 2.71814 30.6958 0 24.2428 0C14.8364 0 6.60001 7.47269 2.72726 18.1091L10.3591 19.1454C12.2908 13.2545 17.8528 9.81812 24.2428 9.81812Z" fill="#EA4335"/></svg>
                <span>{{ __('Log in with Google') }}</span>
            </a>
        </div>
    </form>
</x-guest-layout>
