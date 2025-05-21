<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Main Content -->
                <div class="md:w-2/3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Latest Posts</h1>
                            
                            @if($posts->isEmpty())
                                <p class="text-gray-600 dark:text-gray-400">No posts available.</p>
                            @else
                                <div class="space-y-8">
                                    @foreach($posts as $post)
                                        <div class="border-b dark:border-gray-700 pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                                            <h2 class="text-xl font-semibold mb-2">
                                                <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                    {{ $post->title }}
                                                </a>
                                            </h2>
                                            
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                <span class="mr-4">
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $post->user->name }}
                                                </span>
                                                <span class="mr-4">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $post->published_at->format('M d, Y') }}
                                                </span>
                                                @if($post->category)
                                                <span class="mr-4">
                                                    <i class="fas fa-folder mr-1"></i>
                                                    <a href="{{ route('blog.category', $post->category->slug) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                        {{ $post->category->name }}
                                                    </a>
                                                </span>
                                                @endif
                                                <a href="{{ route('blog.show', $post->slug) }}#comments" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 flex items-center" title="{{ __('Comments') }}">
                                                    <span class="mr-1">💬</span>
                                                    <span>{{ $post->comments_count }}</span>
                                                </a>
                                            </div>
                                            
                                            <div class="prose prose-sm dark:prose-invert max-w-none mb-3">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 250) }}
                                            </div>
                                            
                                            <a href="{{ route('blog.show', $post->slug) }}" class="inline-block text-blue-600 dark:text-blue-400 hover:underline">
                                                Read More →
                                            </a>
                                            
                                            @if($post->tags->count() > 0)
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach($post->tags as $tag)
                                                        <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
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
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Categories</h3>
                            <ul class="space-y-2">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('blog.category', $category->slug) }}" class="flex justify-between text-blue-600 dark:text-blue-400 hover:underline">
                                            <span>{{ $category->name }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">({{ $category->posts_count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Tags Widget -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
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