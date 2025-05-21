@php
    Log::info('Dashboard Blade: Current App Locale at top: ' . App::getLocale());
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ __('Welcome to Your Dashboard') }}
                    </h1>
                    <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                        @if(Auth::user()->role === 'admin')
                            {{ __('From here, you can manage your content, view statistics, and access all the tools you need.') }}
                        @else
                            {{ __('From here, you can view content and access your purchased items.') }}
                        @endif
                    </p>
                </div>
            </div>

            @php
                Log::info('Dashboard Blade: Current App Locale before Stats: ' . App::getLocale());
            @endphp

            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Posts Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Blog Posts') }}</h3>
                                <p class="text-base text-gray-500 dark:text-gray-400">
                                    @if(Auth::user()->role === 'admin')
                                        {{ __('Manage and create new blog content') }}
                                    @else
                                        {{ __('View our blog posts') }}
                                    @endif
                                </p>
                                <div class="mt-3">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('posts.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                            {{ __('Manage Posts') }} →
                                        </a>
                                    @else
                                        <a href="{{ route('blog.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                            {{ __('View Blog') }} →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Galleries') }}</h3>
                                <p class="text-base text-gray-500 dark:text-gray-400">
                                    @if(Auth::user()->role === 'admin')
                                        {{ __('Manage your photo galleries') }}
                                    @else
                                        {{ __('Browse photo galleries') }}
                                    @endif
                                </p>
                                <div class="mt-3">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('galleries.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                            {{ __('Manage Galleries') }} →
                                        </a>
                                    @else
                                        <a href="{{ route('gallery.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                            {{ __('View Galleries') }} →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Products') }}</h3>
                                <p class="text-base text-gray-500 dark:text-gray-400">
                                    @if(Auth::user()->role === 'admin')
                                        {{ __('Manage your digital products') }}
                                    @else
                                        {{ __('Browse digital products') }}
                                    @endif
                                </p>
                                <div class="mt-3">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('products.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                            {{ __('Manage Products') }} →
                                        </a>
                                    @else
                                        <a href="{{ route('shop.index') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                            {{ __('View Shop') }} →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions - Only visible to admin users -->
            @if(Auth::user()->role === 'admin')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <a href="{{ route('posts.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Create New Post') }}
                            </a>
                            <a href="{{ route('galleries.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Add New Gallery') }}
                            </a>
                            <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Add New Category') }}
                            </a>
                            <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Add New Product') }}
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Site Settings') }}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Member-specific actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('My Account') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('shop.my-purchases') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ __('View My Purchases') }}
                            </a>
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Edit Profile') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
