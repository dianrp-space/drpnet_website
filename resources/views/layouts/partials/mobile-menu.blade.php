<!-- User options in mobile menu -->
<div class="pt-4 pb-3 border-gray-200 dark:border-gray-700">
    <!-- Dark mode toggle -->
    <div class="px-4 mb-3">
        <button @click="$store.theme.toggle()" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-gray-700 dark:hover:bg-gray-600">
            <svg x-cloak x-show="!$store.theme.dark" class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg x-cloak x-show="$store.theme.dark" class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span x-show="!$store.theme.dark">{{ __('Mode Gelap') }}</span>
            <span x-show="$store.theme.dark">{{ __('Mode Terang') }}</span>
        </button>
    </div>

    @auth
        <!-- Keranjang Belanja -->
        <div class="px-4 mb-3">
            <a href="{{ route('cart.index') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ __('Keranjang Belanja') }}
                @if(Auth::user()->cart && Auth::user()->cart->total_items > 0)
                    <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ Auth::user()->cart->total_items }}
                    </span>
                @endif
            </a>
        </div>

        <div class="px-4 flex items-center mb-3">
            <div class="shrink-0 me-3">
                @if (Auth::user()->profile_photo_url)
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                @elseif (Auth::user()->profile_photo) 
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" />
                @else
                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div>
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <div class="mt-3 space-y-1 px-2">
            <x-responsive-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')">
                {{ __('Setting Profil') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    @else
        <div class="space-y-1 px-2">
            <x-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                {{ __('Login Member') }}
            </x-responsive-nav-link>

            @if (Route::has('register'))
                <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                    {{ __('Daftar') }}
                </x-responsive-nav-link>
            @endif
        </div>
    @endauth
</div> 