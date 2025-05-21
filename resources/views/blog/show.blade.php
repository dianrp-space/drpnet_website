<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="blogComments()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Main Content -->
                <div class="md:w-2/3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h1 class="text-3xl font-bold mb-4 text-gray-800 dark:text-gray-200">{{ $post->title }}</h1>
                            
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-6">
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
                                <a href="#comments" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 flex items-center">
                                    <span class="mr-1">💬</span> 
                                    <span>{{ $post->comments_count }}</span>
                                </a>
                            </div>
                            
                            <div class="prose prose-lg dark:prose-invert max-w-none mb-6">
                                {!! $post->content !!}
                            </div>
                            
                            @if($post->tags->count() > 0)
                                <div class="mt-8 pt-4 border-t dark:border-gray-700">
                                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Tags:</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($post->tags as $tag)
                                            <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
                                                #{{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-8">
                                <a href="{{ route('blog.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Back to Blog
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comments Section -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-gray-200">{{ __('Comments') }} ({{ $post->comments_count }})</h2>
                            
                            <!-- Comment Form -->
                            <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">{{ __('Add a comment') }}</h3>
                                <form @submit.prevent="submitComment({{ $post->id }})">
                                    <div class="mb-4">
                                        <textarea 
                                            x-model="commentText"
                                            rows="4" 
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm dark:bg-gray-800 dark:text-gray-200"
                                            placeholder="{{ __('Share your thoughts about this post...') }}"
                                        ></textarea>
                                    </div>
                                    <button 
                                        type="submit" 
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                                        :disabled="!commentText.trim() || isLoading"
                                        :class="{'opacity-50 cursor-not-allowed': !commentText.trim() || isLoading, 'hover:bg-indigo-700': commentText.trim() && !isLoading}"
                                    >
                                        <span x-show="isLoading" class="mr-2">
                                            <svg class="animate-spin h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                        {{ __('Submit Comment') }}
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Comments List -->
                            <div>
                                <!-- Loading indicator -->
                                <div x-show="isLoading" class="text-center py-4">
                                    <svg class="animate-spin mx-auto h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('Loading comments...') }}</p>
                                </div>
                                
                                <!-- No comments message -->
                                <template x-if="!isLoading && comments.length === 0">
                                    <div class="text-center py-8">
                                        <p class="text-gray-600 dark:text-gray-400">{{ __('No comments yet. Be the first to comment!') }}</p>
                                    </div>
                                </template>
                                
                                <!-- Comment list -->
                                <template x-if="!isLoading && comments.length > 0">
                                    <ul class="space-y-6">
                                        <template x-for="(comment, index) in comments" :key="'comment-' + comment.id">
                                            <li class="border-b dark:border-gray-700 pb-6 last:border-0">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0 mr-3">
                                                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                                            <span x-text="comment.name.charAt(0).toUpperCase()"></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <div class="flex items-center justify-between mb-1">
                                                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200" x-text="comment.name"></h4>
                                                            <span class="text-xs text-gray-500" x-text="comment.date"></span>
                                                        </div>
                                                        <div class="text-gray-700 dark:text-gray-300" x-text="comment.text"></div>
                                                    </div>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    @if($related->count() > 0)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">Related Posts</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    @foreach($related as $relatedPost)
                                        <div class="border dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-700">
                                            <div class="p-4">
                                                <h3 class="text-lg font-semibold mb-2">
                                                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                        {{ $relatedPost->title }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                    {{ $relatedPost->published_at->format('M d, Y') }}
                                                    <a href="{{ route('blog.show', $relatedPost->slug) }}#comments" class="ml-2 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 inline-flex items-center">
                                                        <span class="mr-1">💬</span>
                                                        <span>{{ $relatedPost->comments_count }}</span>
                                                    </a>
                                                </p>
                                                <div class="text-sm text-gray-700 dark:text-gray-300 prose prose-sm dark:prose-invert max-w-none">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($relatedPost->content), 100) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="md:w-1/3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-8">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">About the Author</h3>
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold text-xl">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ $post->user->name }}</h4>
                                </div>
                            </div>
                            
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p>Author of this article and contributor to our blog. For more articles by this author, check out other posts.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function blogComments() {
            return {
                commentText: '',
                comments: [],
                isLoading: true,
                
                submitComment(postId) {
                    if (!this.commentText.trim()) return;
                    
                    this.isLoading = true;
                    fetch(`/blog/${postId}/comments`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            comment: this.commentText
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw response;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.comments.unshift(data.comment);
                            this.commentText = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (error.status === 422) {
                            // Validation error
                            error.json().then(data => {
                                if (data.errors && data.errors.comment) {
                                    alert(data.errors.comment[0]);
                                } else {
                                    alert('{{ __('Failed to submit comment. Please try again.') }}');
                                }
                            });
                        } else {
                            alert('{{ __('Failed to submit comment. Please try again.') }}');
                        }
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
                },
                
                init() {
                    this.loadComments({{ $post->id }});
                },
                
                loadComments(postId) {
                    this.isLoading = true;
                    fetch(`/blog/${postId}/comments`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.comments = data.comments;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
                }
            };
        }
    </script>
    @endpush
</x-app-layout> 