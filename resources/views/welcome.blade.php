<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DRP Network Solutions') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('storage/' . \App\Models\Setting::get('site_favicon', 'images/favicon.ico')) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // Initialize theme before Alpine loads to prevent flash of wrong theme
            if (localStorage.getItem('theme') === 'dark' || 
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <style>
            .text-shadow {
                text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            }
            .text-shadow-lg {
                text-shadow: 0 2px 4px rgba(0,0,0,0.4);
            }
        </style>
    </head>
    <body class="font-sans antialiased" x-data>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Navigation -->
            <nav class="absolute top-0 left-0 right-0 z-50 bg-gray-900/70 dark:bg-black/70 backdrop-blur-sm border-b border-gray-200/10 dark:border-gray-700/30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ url('/') }}" class="flex items-center">
                                    @php
                                        $logoPath = \App\Models\Setting::get('site_logo');
                                    @endphp
                                    
                                    @if($logoPath)
                                        <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ config('app.name') }}" class="h-10">
                                    @else
                                        <span class="text-2xl font-bold text-white">
                                            DRP Network Solutions
                                        </span>
                                    @endif
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('blog.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-100 hover:text-white hover:border-white">
                                    {{ __('Blog') }}
                                </a>
                                <a href="{{ route('gallery.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-100 hover:text-white hover:border-white">
                                    {{ __('Gallery') }}
                                </a>
                                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-100 hover:text-white hover:border-white">
                                    {{ __('Shop') }}
                                </a>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Dark mode toggle -->
                            <button @click="$store.theme.toggle()" class="inline-flex items-center justify-center p-2 mx-2 rounded-md text-gray-100 hover:text-white transition duration-150 ease-in-out">
                                <svg x-show="!$store.theme.dark" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg x-show="$store.theme.dark" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>
                            
                            @if (Route::has('login'))
                                <div class="flex space-x-4">
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Member Area') }}
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Login Member') }}
                                        </a>

                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Register') }}
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="relative min-h-[80vh] flex items-center">
                <!-- Image Slider as background - Full width -->
                <div x-data="imageSlider()" class="absolute inset-0 w-full h-full z-0">
                    @php
                        $slides = \App\Models\Slide::getActiveSlides();
                    @endphp
                    
                    @forelse($slides as $index => $slide)
                        <div x-show="currentSlide === {{ $index }}" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute inset-0 w-full h-full">
                            <img src="{{ asset('storage/' . $slide->image_path) }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                            
                            @if($slide->title || $slide->description)
                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent text-white">
                                    @if($slide->title)
                                        <h3 class="text-xl font-semibold mb-2">{{ $slide->title }}</h3>
                                    @endif
                                    
                                    @if($slide->description)
                                        <p class="text-sm">{{ $slide->description }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <img class="h-full w-full object-cover" src="{{ asset('images/vpn_network.png') }}" alt="">
                    @endforelse
                    
                    @if(count($slides) > 1)
                        <!-- Slider controls -->
                        <button @click="prevSlide" class="absolute left-4 top-1/2 transform -translate-y-1/2 p-2 rounded-full bg-black/30 text-white hover:bg-black/50 z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        
                        <button @click="nextSlide" class="absolute right-4 top-1/2 transform -translate-y-1/2 p-2 rounded-full bg-black/30 text-white hover:bg-black/50 z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        
                        <!-- Indicators -->
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                            @foreach($slides as $index => $slide)
                                <button @click="goToSlide({{ $index }})" 
                                        class="w-3 h-3 rounded-full"
                                        :class="{'bg-white': currentSlide === {{ $index }}, 'bg-white/50': currentSlide !== {{ $index }}}">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <!-- Content overlay centered -->
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="bg-black/40 dark:bg-black/50 backdrop-blur-sm p-8 rounded-lg max-w-xl mx-auto text-center">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl text-shadow-lg">
                            <span class="block">{{ __('Welcome to') }}</span>
                            <span class="block text-indigo-300">DRP Network Solutions</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl text-shadow">
                            {{ __('Explore my creative work, blog posts, photo galleries, and digital products.') }}
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center">
                            <div class="rounded-md shadow">
                                <a href="{{ route('blog.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    {{ __('Read Blog') }}
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('gallery.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-100 bg-indigo-500/70 hover:bg-indigo-500 md:py-4 md:text-lg md:px-10">
                                    {{ __('View Gallery') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-16 bg-gray-100 dark:bg-gray-900/80 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase">{{ __('Features') }}</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                            {{ __('Explore What I Offer') }}
                        </p>
                        <p class="mt-4 max-w-2xl text-xl text-gray-600 dark:text-gray-300 mx-auto">
                            {{ __('Discover my blog, gallery, and digital products.') }}
                        </p>
                    </div>

                    <div class="mt-10">
                        <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                            <!-- Blog Feature -->
                            <div class="relative group">
                                <div class="bg-white dark:bg-gray-800/60 backdrop-blur-sm rounded-lg p-6 ring-1 ring-gray-200 dark:ring-gray-700/30 shadow-lg hover:bg-gray-50 dark:hover:bg-gray-800/80 transition-all duration-300 hover:shadow-xl">
                                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white -top-6 left-6">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-8 text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Blog') }}</h3>
                                    <p class="mt-2 text-base text-gray-600 dark:text-gray-300">
                                        {{ __('Read my thoughts, tutorials, and insights on various topics.') }}
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('blog.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 flex items-center">
                                            {{ __('Visit Blog') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Gallery Feature -->
                            <div class="relative group">
                                <div class="bg-white dark:bg-gray-800/60 backdrop-blur-sm rounded-lg p-6 ring-1 ring-gray-200 dark:ring-gray-700/30 shadow-lg hover:bg-gray-50 dark:hover:bg-gray-800/80 transition-all duration-300 hover:shadow-xl">
                                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white -top-6 left-6">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-8 text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Gallery') }}</h3>
                                    <p class="mt-2 text-base text-gray-600 dark:text-gray-300">
                                        {{ __('Browse my collection of photos and creative work.') }}
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('gallery.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 flex items-center">
                                            {{ __('View Gallery') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Shop Feature -->
                            <div class="relative group">
                                <div class="bg-white dark:bg-gray-800/60 backdrop-blur-sm rounded-lg p-6 ring-1 ring-gray-200 dark:ring-gray-700/30 shadow-lg hover:bg-gray-50 dark:hover:bg-gray-800/80 transition-all duration-300 hover:shadow-xl">
                                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white -top-6 left-6">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-8 text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Digital Shop') }}</h3>
                                    <p class="mt-2 text-base text-gray-600 dark:text-gray-300">
                                        {{ __('Purchase my digital products, templates, and resources.') }}
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('shop.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 flex items-center">
                                            {{ __('Shop Now') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-t border-gray-200/30 dark:border-gray-800/30">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                    <div class="flex justify-center space-x-6 md:order-2">
                        <a href="https://x.com/dian_erpe" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">X (Twitter)</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="https://facebook.com/dwaizzman" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="https://instagram.com/dianramaputra" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="https://youtube.com/@drpnet" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">YouTube</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="https://github.com/dianrp-space" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">GitHub</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div class="mt-8 md:mt-0 md:order-1">
                        <p class="text-center text-base text-gray-300">
                            &copy; {{ date('Y') }} DRP Network Solutions. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
        
        <script>
            function imageSlider() {
                return {
                    currentSlide: 0,
                    totalSlides: {{ count($slides ?? []) ?: 1 }},
                    autoplayInterval: null,
                    
                    init() {
                        if (this.totalSlides > 1) {
                            this.startAutoplay();
                        }
                    },
                    
                    startAutoplay() {
                        this.autoplayInterval = setInterval(() => {
                            this.nextSlide();
                        }, 5000); // Change slide every 5 seconds
                    },
                    
                    stopAutoplay() {
                        if (this.autoplayInterval) {
                            clearInterval(this.autoplayInterval);
                        }
                    },
                    
                    nextSlide() {
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    },
                    
                    prevSlide() {
                        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                    },
                    
                    goToSlide(index) {
                        this.currentSlide = index;
                    }
                };
            }
        </script>
        @stack('scripts')
        <x-tawk-chat />
    </body>
</html>
