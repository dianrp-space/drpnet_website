<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 z-50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Tombol Toggle Sidebar -->
                <button @click="$store.sidebar.toggle()" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-200 transition duration-150 ease-in-out">
                    <svg x-show="!$store.sidebar.open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <svg x-show="$store.sidebar.open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Logo -->
                <div class="shrink-0 flex items-center ml-2">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @include('layouts.partials.settings-dropdown')
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Left Sidebar Navigation -->
    <div x-cloak x-show="$store.sidebar.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-full"
         style="display: none;"
         class="fixed top-20 left-4 w-[250px] max-h-[80vh] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-lg z-40 overflow-y-auto">
        
        <!-- Sidebar content -->
        <div class="h-full overflow-y-auto">
            <div class="px-4 py-6">
                <div class="mb-6">
                    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2">{{ __('UTAMA') }}</h3>
                    <div class="space-y-1">
                        <x-nav-link :href="url('/')" :active="request()->routeIs('welcome')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('welcome') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Halaman Utama') }}
                        </x-nav-link>
                        <x-nav-link :href="url('/dashboard')" :active="request()->routeIs('dashboard')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Dasbor') }}
                        </x-nav-link>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2">{{ __('KONTEN PUBLIK') }}</h3>
                    <div class="space-y-1">
                        <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('shop.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Toko') }}
                        </x-nav-link>
                        <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('blog.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Blog') }}
                        </x-nav-link>
                        <x-nav-link :href="route('gallery.index')" :active="request()->routeIs('gallery.*')" 
                            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('gallery.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ __('Galeri') }}
                        </x-nav-link>
                    </div>
                </div>

                @auth
                    @include('layouts.partials.user-menu')
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-cloak x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="display: none;" 
         class="sm:hidden fixed top-20 right-4 w-[250px] max-h-[calc(100vh-6rem)] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-lg z-[55] overflow-y-auto">
        @include('layouts.partials.mobile-menu')
    </div>

    <!-- Backdrop for mobile menu -->
    <div x-cloak x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         style="display: none;"
         class="sm:hidden fixed inset-0 bg-black/30 dark:bg-black/50 z-[50]">
    </div>
</nav>

<!-- Main Content Container -->
<main class="w-full">
</main>
