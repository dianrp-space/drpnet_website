<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $gallery->title }}
            </h2>
            <a href="{{ route('gallery.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none transition ease-in-out duration-150">
                Back to Gallery
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Gallery details section -->
                    <div class="mb-8">
                        <!-- Main gallery image -->
                        <div class="mb-6">
                            @if($gallery->image_path)
                                <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-auto max-h-96 object-contain rounded-lg shadow-lg mx-auto">
                            @else
                                <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-lg">
                                    <span class="text-gray-500 dark:text-gray-400">No image available</span>
                                </div>
                            @endif
                        </div>

                        <!-- Gallery details -->
                        <div class="mb-4">
                            <h2 class="text-2xl font-bold mb-4">{{ $gallery->title }}</h2>
                            
                            @if($gallery->description)
                                <div class="prose dark:prose-invert max-w-none mb-4">
                                    <p>{{ $gallery->description }}</p>
                                </div>
                            @endif
                            
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $gallery->created_at->format('F d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>Posted by {{ $gallery->user->name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery photos section -->
                    <div>
                        <h3 class="text-xl font-semibold mb-4">Photos</h3>
                        
                        @if($gallery->photos && $gallery->photos->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($gallery->photos as $photo)
                                    <div class="overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                        <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->caption }}" class="w-full h-48 object-cover">
                                        @if($photo->caption)
                                            <div class="p-2 bg-white dark:bg-gray-700">
                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $photo->caption }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No additional photos in this gallery.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 