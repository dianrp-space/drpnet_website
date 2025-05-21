<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category: ') . $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Main Content -->
                <div class="md:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h1 class="text-2xl font-bold">{{ $category->name }}</h1>
                                <a href="{{ route('blog.index') }}" class="inline-flex items-center text-blue-600 hover:underline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Back to All Posts
                                </a>
                            </div>
                            
                            @if($posts->isEmpty())
                                <p class="text-gray-600">No posts found in this category.</p>
                            @else
                                <div class="space-y-8">
                                    @foreach($posts as $post)
                                        <div class="border-b pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                                            <h2 class="text-xl font-semibold mb-2">
                                                <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $post->title }}
                                                </a>
                                            </h2>
                                            
                                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                                <span class="mr-4">
                                                    <i class="fas fa-user mr-1"></i> 
                                                    {{ $post->user->name }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-calendar mr-1"></i> 
                                                    {{ $post->published_at->format('M d, Y') }}
                                                </span>
                                            </div>
                                            
                                            <div class="prose prose-sm max-w-none mb-3">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 250) }}
                                            </div>
                                            
                                            <a href="{{ route('blog.show', $post->slug) }}" class="inline-block text-blue-600 hover:underline">
                                                Read More →
                                            </a>
                                            
                                            @if($post->tags->count() > 0)
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach($post->tags as $tag)
                                                        <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full hover:bg-gray-200">
                                                            #{{ $tag->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-6">
                                    {{ $posts->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="md:w-1/3">
                    <!-- Categories Widget -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-3">Categories</h3>
                            <ul class="space-y-2">
                                @foreach($categories as $cat)
                                    <li>
                                        <a href="{{ route('blog.category', $cat->slug) }}" 
                                           class="flex justify-between text-blue-600 hover:underline {{ $cat->id === $category->id ? 'font-bold' : '' }}">
                                            <span>{{ $cat->name }}</span>
                                            <span class="text-gray-500">({{ $cat->posts_count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Tags Widget -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-3">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-gray-200">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 