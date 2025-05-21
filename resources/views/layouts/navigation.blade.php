<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Hamburger for sidebar (main toggle for all sizes) -->
                <button @click="$store.sidebar.toggle()" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-200 transition duration-150 ease-in-out ml-2">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <!-- Icon: Chevron Double Left (when sidebar is open) -->
                        <path x-show="$store.sidebar.open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" x-cloak/>
                        <!-- Icon: Menu (Hamburger) (when sidebar is closed) -->
                        <path x-show="!$store.sidebar.open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" x-cloak/>
                    </svg>
                </button>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
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
                    <span class="text-xl">🛒</span>
                    @if(Auth::check() && Auth::user()->cart && Auth::user()->cart->total_items > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                            {{ Auth::user()->cart->total_items }}
                        </span>
                    @endif
                </a>
                @endauth

                <!-- Language Switcher Dropdown -->
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
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            @if(Auth::check())
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
            </div>

            <!-- Hamburger for mobile menu -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Left Sidebar Navigation -->
    <div x-cloak x-show="$store.sidebar.open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-full"
         class="fixed inset-y-0 left-0 pt-16 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-lg overflow-y-auto z-40">
        
        <!-- New Sidebar Toggle Button at the top for mobile -->
        <div class="lg:hidden p-2 border-b border-gray-200 dark:border-gray-700">
            <button @click="$store.sidebar.toggle()" 
                    class="flex items-center justify-start w-full py-2 px-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-200 dark:focus:bg-gray-700 rounded-md transition-colors duration-150 ease-in-out">
                @svg('heroicon-o-arrow-left-start-on-rectangle', ['class' => 'h-5 w-5 mr-2'])
                <span>{{ __('Hide Sidebar') }}</span>
            </button>
        </div>

        <!-- Sidebar content -->
        <div class="px-2 py-4">
            <!-- Main Navigation Group -->
            <div class="mb-4">
                <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2 px-4">{{ __('Main') }}</h3>
                <div>
                    <x-nav-link :href="url('/')" :active="request()->routeIs('welcome')" 
                        class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('welcome') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ __('Home') }}
                    </x-nav-link>
                </div>
                <div>
                    <x-nav-link :href="url('/dashboard')" :active="request()->routeIs('dashboard')" 
                        class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Public Content Group -->
            <div class="mb-4">
                <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2 px-4">{{ __('Public Content') }}</h3>
                <div>
                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*') || request()->routeIs('shop.index')" 
                        class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('products.*') || request()->routeIs('shop.index') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ __('Shop') }}
                    </x-nav-link>
                    @else
                    <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.index')" 
                        class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('shop.index') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ __('Shop') }}
                    </x-nav-link>
                    @endif
                    
                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*') || request()->routeIs('blog.*')" 
                        class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('posts.*') || request()->routeIs('blog.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ __('Blog') }}
                    </x-nav-link>
                    @else
                    <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')" 
                        class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('blog.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ __('Blog') }}
                    </x-nav-link>
                    @endif
                    
                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <x-nav-link :href="route('galleries.index')" :active="request()->routeIs('galleries.*') || request()->routeIs('gallery.*')" 
                        class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('galleries.*') || request()->routeIs('gallery.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ __('Gallery') }}
                    </x-nav-link>
                    @else
                    <x-nav-link :href="route('gallery.index')" :active="request()->routeIs('gallery.*')" 
                        class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('gallery.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ __('Gallery') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- User Specific Group -->
            @if(Auth::check())
                <div class="mb-4">
                    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2 px-4">{{ __('My Account') }}</h3>
                    <div>
                        <x-nav-link :href="route('balance.index')" :active="request()->routeIs('balance.*')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('balance.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('My Balance') }}
                        </x-nav-link>
                        <x-nav-link :href="route('deposit.form')" :active="request()->routeIs('deposit.*')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('deposit.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Deposit Funds') }}
                        </x-nav-link>
                        <x-nav-link :href="route('transfer.form')" :active="request()->routeIs('transfer.*')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('transfer.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Transfer Funds') }}
                        </x-nav-link>
                        <x-nav-link :href="route('shop.my-purchases')" :active="request()->routeIs('shop.my-purchases')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('shop.my-purchases') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('My Purchases') }}
                        </x-nav-link>
                        <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('cart.index') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Shopping Cart') }}
                            @if(Auth::user()->cart && Auth::user()->cart->total_items > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ Auth::user()->cart->total_items }}
                                </span>
                            @endif
                        </x-nav-link>
                        <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('profile.edit') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Profile Settings') }}
                        </x-nav-link>
                    </div>
                </div>
            @endif

            <!-- Admin Group -->
            @if(Auth::check() && Auth::user()->role === 'admin')
                <div class="mb-4">
                    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2 px-4">{{ __('Taxonomies') }}</h3>
                    <div>
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('categories.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.*')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('tags.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Tags') }}
                        </x-nav-link>
                    </div>
                </div>
            @endif

            @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="mt-8">
                <div class="px-3 mb-2">
                    <h3 class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">
                        {{ __('Administration') }}
                    </h3>
                </div>
                
                <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')" 
                    class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    {{ __('Site Settings') }}
                </x-nav-link>
            </div>
            @endif
        </div>
    </div>

    <!-- Floating button to show sidebar when closed -->
    <button x-cloak x-show="!$store.sidebar.open" @click="$store.sidebar.toggle()" title="{{ __('Show Sidebar') }}"
            class="fixed bottom-4 left-4 z-50 p-3 bg-indigo-600 dark:bg-indigo-500 text-white rounded-full shadow-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-opacity-50 transition-transform transform hover:scale-110">
        @svg('heroicon-s-user', ['class' => 'h-6 w-6'])
    </button>

    <!-- Responsive Navigation Menu (mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <!-- Public Content Links -->
            @if(Auth::check() && Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*') || request()->routeIs('shop.index')">
                {{ __('Shop') }}
            </x-responsive-nav-link>
            @else
            <x-responsive-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.index')">
                {{ __('Shop') }}
            </x-responsive-nav-link>
            @endif
            
            @if(Auth::check() && Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*') || request()->routeIs('blog.*')">
                {{ __('Blog') }}
            </x-responsive-nav-link>
            @else
            <x-responsive-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')">
                {{ __('Blog') }}
            </x-responsive-nav-link>
            @endif
            
            @if(Auth::check() && Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('galleries.index')" :active="request()->routeIs('galleries.*') || request()->routeIs('gallery.*')">
                {{ __('Gallery') }}
            </x-responsive-nav-link>
            @else
            <x-responsive-nav-link :href="route('gallery.index')" :active="request()->routeIs('gallery.*')">
                {{ __('Gallery') }}
            </x-responsive-nav-link>
            @endif
            
            @if(Auth::check())
                <x-responsive-nav-link :href="route('balance.index')" :active="request()->routeIs('balance.*')">
                    {{ __('My Balance') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('deposit.form')" :active="request()->routeIs('deposit.*')">
                    {{ __('Deposit Funds') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('transfer.form')" :active="request()->routeIs('transfer.*')">
                    {{ __('Transfer Funds') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('shop.my-purchases')" :active="request()->routeIs('shop.my-purchases')">
                    {{ __('My Purchases') }}
                </x-responsive-nav-link>
            @endif
            
            @if(Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.*')">
                    {{ __('Tags') }}
                </x-responsive-nav-link>
            @endif

            <!-- Add dark mode toggle for mobile -->
            <div class="pt-2 pb-3 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center px-4">
                    <button @click="$store.theme.toggle()" class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-left text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out">
                        <span>{{ __('Toggle Dark Mode') }}</span>
                        <svg x-cloak x-show="!$store.theme.dark" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-cloak x-show="$store.theme.dark" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                @if(Auth::check())
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                @if(Auth::check())
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log In') }}
                    </x-responsive-nav-link>
                    
                    @if(Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
