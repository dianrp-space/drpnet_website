<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Digital Products & Services')</title>
        <meta name="description" content="@yield('meta_description', 'DRP Network Solutions menyediakan produk digital dan layanan profesional untuk kebutuhan bisnis Anda.')">
        <meta name="keywords" content="@yield('meta_keywords', 'produk digital, jasa web, development, hosting, domain')">
        
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ config('app.name') }} - @yield('title', 'Digital Products & Services')">
        <meta property="og:description" content="@yield('meta_description', 'DRP Network Solutions menyediakan produk digital dan layanan profesional untuk kebutuhan bisnis Anda.')">
        <meta property="og:image" content="@yield('meta_image', asset('images/og-image.jpg'))">
        <meta property="og:url" content="{{ url()->current() }}">
        
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.name') }} - @yield('title', 'Digital Products & Services')">
        <meta name="twitter:description" content="@yield('meta_description', 'DRP Network Solutions menyediakan produk digital dan layanan profesional untuk kebutuhan bisnis Anda.')">
        <meta name="twitter:image" content="@yield('meta_image', asset('images/og-image.jpg'))">

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('storage/' . \App\Models\Setting::get('site_favicon', 'images/favicon.ico')) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}" />

        <!-- Preload -->
        <link rel="preload" href="{{ asset('fonts/your-main-font.woff2') }}" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
        <link rel="preload" href="{{ asset('js/app.js') }}" as="script">
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 h-full">
        <!-- Global Loader -->
        <div id="global-loader" style="position:fixed;z-index:99999;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.85);backdrop-filter:blur(2px);transition:opacity .3s;">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-14 w-14 text-indigo-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-lg font-semibold text-indigo-700">Memuat halaman...</span>
            </div>
        </div>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    const loader = document.getElementById('global-loader');
                    if(loader) loader.style.opacity = 0;
                    setTimeout(() => { if(loader) loader.style.display = 'none'; }, 400);
                }, 600); // Loader minimal tampil 600ms agar smooth
            });
        </script>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <div class="flex">
                <!-- Main content area - now full width with padding when sidebar is open -->
                <div class="flex-1 transition-all duration-300" :class="{'ml-64': $store.sidebar.open}">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white dark:bg-gray-800 shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            @if (isset($slot))
                                {{ $slot }}
                            @else
                                @yield('content')
                            @endif
                        </div>
                    </main>
                </div>
            </div>
        </div>
        @stack('scripts')
        @if(Auth::check() && Auth::user()->role !== 'admin')
            <x-tawk-chat />
        @endif
    </body>
</html>
