<!-- Dark mode toggle -->
<button @click="$store.theme.toggle()" class="inline-flex items-center justify-center p-2 mx-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition duration-150 ease-in-out">
    <svg x-cloak x-show="!$store.theme.dark" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
    <svg x-cloak x-show="$store.theme.dark" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
</button>

@auth
<!-- Shopping Cart -->
<a href="{{ route('cart.index') }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition duration-150 ease-in-out mx-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
    @if(Auth::user()->cart && Auth::user()->cart->total_items > 0)
        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
            {{ Auth::user()->cart->total_items }}
        </span>
    @endif
</a>
@endauth

<!-- Language Switcher -->
<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
            <div>{{ strtoupper(app()->getLocale()) }}</div>
            <div class="ms-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </button>
    </x-slot>
    <x-slot name="content">
        <x-dropdown-link href="{{ route('locale.set', 'id') }}?_={{ time() }}" onclick="event.preventDefault(); window.location.href='{{ route('locale.set', 'id') }}?_={{ time() }}';">
            {{ __('Indonesia') }}
        </x-dropdown-link>
        <x-dropdown-link href="{{ route('locale.set', 'en') }}?_={{ time() }}" onclick="event.preventDefault(); window.location.href='{{ route('locale.set', 'en') }}?_={{ time() }}';">
            {{ __('English') }}
        </x-dropdown-link>
    </x-slot>
</x-dropdown>

<!-- User Menu -->
<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
            @if(Auth::check())
                @if(Auth::user()->profile_photo)
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Foto Profil" class="w-8 h-8 rounded-full object-cover border mr-2" />
                @else
                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-base mr-2">
                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                    </div>
                @endif
                <div>{{ Auth::user()->name }}</div>
            @else
                <div>{{ __('Menu') }}</div>
            @endif
            <div class="ms-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </button>
    </x-slot>

    <x-slot name="content">
        @if(Auth::check())
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        @else
            <x-dropdown-link :href="route('login')">
                {{ __('Log In') }}
            </x-dropdown-link>
            
            @if(Route::has('register'))
                <x-dropdown-link :href="route('register')">
                    {{ __('Register') }}
                </x-dropdown-link>
            @endif
        @endif
    </x-slot>
</x-dropdown> 