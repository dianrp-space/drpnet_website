<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gallery') }}
        </h2>
    </x-slot>

    <div x-data="galleryApp()" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">{{ __('Photo Gallery') }}</h1>
                    
                    @if($galleries->isEmpty())
                        <p class="text-center py-8 text-gray-600 dark:text-gray-400">{{ __('No gallery items available.') }}</p>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($galleries as $gallery)
                                @php
                                    $safeDescription = e(str_replace(["\r", "\n"], ' ', $gallery->description));
                                @endphp
                                <div class="group relative bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 aspect-w-1 aspect-h-1">
                                    @if($gallery->image_path)
                                        <img src="{{ asset('storage/' . $gallery->image_path) }}" 
                                             alt="{{ $gallery->title }}" 
                                             class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
                                             @click.stop="openLightbox({{ $gallery->id }}, '{{ asset('storage/' . $gallery->image_path) }}', '{{ e($gallery->title) }}', '{{ $safeDescription }}')">
                                        
                                        <!-- Overlay for icons -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex flex-col items-center justify-center p-2 opacity-0 group-hover:opacity-100 cursor-pointer" 
                                             @click.stop="openLightbox({{ $gallery->id }}, '{{ asset('storage/' . $gallery->image_path) }}', '{{ e($gallery->title) }}', '{{ $safeDescription }}')">
                                            
                                            <div class="text-white text-center">
                                                <!-- Icon Lihat Gambar -->
                                                <svg class="w-10 h-10 mb-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                <p class="text-xs font-semibold">{{ __('View Image') }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Info Komentar di bawah gambar -->
                                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/70 to-transparent group-hover:from-black/80 transition-all duration-300">
                                            <div class="flex items-center justify-end text-xs text-white">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path></svg>
                                                <span>{{ $gallery->comments_count }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-gray-400 dark:text-gray-300">{{ __('No image') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $galleries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div x-cloak
             x-show="showLightbox"
             @keydown.escape.window="showLightbox = false"
             class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 p-4 sm:p-6 md:p-8 transition-opacity duration-300 ease-out"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col md:flex-row overflow-hidden" @click.away="showLightbox = false">
                <button @click="showLightbox = false" class="absolute top-2 right-2 text-gray-300 hover:text-white bg-gray-800 bg-opacity-50 hover:bg-opacity-75 rounded-full p-1.5 z-20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <!-- Image Container -->
                <div class="w-full md:w-2/3 flex-shrink-0 bg-black flex items-center justify-center p-2 md:p-0">
                    <img :src="currentImage" alt="Lightbox Image" class="max-w-full max-h-[40vh] sm:max-h-[50vh] md:max-h-[calc(90vh-2rem)] object-contain rounded-l-lg md:rounded-l-none">
                </div>

                <!-- Text Info Container -->
                <div class="w-full md:w-1/3 p-4 md:p-6 text-gray-900 dark:text-gray-100 overflow-y-auto flex-grow flex flex-col">
                    <h3 class="text-xl lg:text-2xl font-semibold mb-2 md:mb-4" x-text="currentTitle"></h3>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 mb-4 flex-shrink-0" x-html="currentDescription"></div>
                    
                    <!-- Komentar Section -->
                    <div class="mt-auto flex flex-col flex-1 min-h-0">
                        <h4 class="font-medium text-lg mb-2 flex-shrink-0">{{ __('Comments') }}</h4>
                        <div class="border dark:border-gray-700 rounded-lg p-3 mb-4 flex-1 min-h-[150px] overflow-y-auto bg-gray-50 dark:bg-gray-700">
                            <!-- Loading indicator -->
                            <div x-show="isLoading" class="text-center py-2">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-indigo-500 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ __('Loading comments...') }}</span>
                            </div>
                            
                            <!-- Tampilkan pesan jika belum ada komentar -->
                            <template x-if="!isLoading && comments.length === 0">
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('No comments yet. Be the first to comment!') }}</p>
                            </template>
                            
                            <!-- Tampilkan daftar komentar -->
                            <div x-show="!isLoading && comments.length > 0" class="space-y-3">
                                <template x-for="(item, index) in comments" :key="index">
                                    <div class="border-b dark:border-gray-600 pb-2 mb-2 last:border-0 last:mb-0 last:pb-0">
                                        <div class="flex items-center mb-1">
                                            <template x-if="item.profile_photo_url">
                                                <img :src="item.profile_photo_url" alt="Foto Profil" class="w-8 h-8 rounded-full object-cover border mr-2" />
                                            </template>
                                            <template x-if="!item.profile_photo_url">
                                                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm mr-2">
                                                    <span x-text="item.name ? item.name.charAt(0).toUpperCase() : 'U'"></span>
                                                </div>
                                            </template>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium" x-text="item.name || 'User'"></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="item.date"></p>
                                            </div>
                                            @if(auth()->check() && auth()->user()->role === 'admin')
                                            <button @click.prevent="deleteComment(item.id, index)" class="ml-2 text-red-500 hover:text-red-700" title="Hapus Komentar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                        <p class="text-sm ml-10 text-gray-700 dark:text-gray-300" x-text="item.text"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Form Komentar -->
                        <form @submit.prevent="submitComment()" class="mt-2 flex-shrink-0">
                            <div class="mb-2">
                                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Add a comment') }}</label>
                                <textarea 
                                    x-model="comment"
                                    id="comment" 
                                    rows="3" 
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm dark:bg-gray-800 dark:text-gray-200"
                                    placeholder="{{ __('Share your thoughts about this image...') }}"
                                ></textarea>
                            </div>
                            <button 
                                type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                                :disabled="!comment.trim() || isLoading"
                                :class="{'opacity-50 cursor-not-allowed': !comment.trim() || isLoading, 'hover:bg-indigo-700': comment.trim() && !isLoading}"
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
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function galleryApp() {
            return {
                showLightbox: false,
                currentImage: '',
                currentTitle: '',
                currentDescription: '',
                currentGalleryId: null,
                comment: '',
                comments: [],
                isLoading: false,
                
                openLightbox(galleryId, image, title, description) {
                    this.showLightbox = true;
                    this.currentImage = image;
                    this.currentTitle = title;
                    this.currentDescription = description;
                    this.currentGalleryId = galleryId;
                    this.comment = '';
                    this.loadComments();
                },
                
                submitComment() {
                    if (!this.comment.trim()) return;
                    
                    this.isLoading = true;
                    fetch(`/gallery/${this.currentGalleryId}/comments`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            comment: this.comment
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
                            this.comment = '';
                        }
                    })
                    .catch(error => {
                        if (error.status === 403) {
                            error.json().then(data => {
                                if (data.message) {
                                    const loginUrl = '{{ route('login') }}';
                                    const msg = `${data.message}<br><a href="${loginUrl}" class="text-blue-600 underline">Login di sini</a>`;
                                    showCustomAlert(msg);
                                } else {
                                    alert('Anda harus login untuk memberikan komentar.');
                                }
                            });
                        } else if (error.status === 422) {
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
                
                loadComments() {
                    if (!this.currentGalleryId) return;
                    
                    this.isLoading = true;
                    fetch(`/gallery/${this.currentGalleryId}/comments`)
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
                },
                deleteComment(commentId, index) {
                    if (!confirm('Yakin ingin menghapus komentar ini?')) return;
                    fetch(`/gallery/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.comments.splice(index, 1);
                        } else {
                            alert('Gagal menghapus komentar.');
                        }
                    })
                    .catch(() => alert('Gagal menghapus komentar.'));
                }
            };
        }

        function showCustomAlert(message) {
            let alertBox = document.createElement('div');
            alertBox.innerHTML = `<div style="position:fixed;top:30px;left:50%;transform:translateX(-50%);z-index:9999;" class='bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded shadow-lg flex items-center'>
                <span class='mr-2'>⚠️</span>
                <span>${message}</span>
                <button onclick='this.parentElement.parentElement.remove()' class='ml-4 text-red-700 font-bold'>&times;</button>
            </div>`;
            document.body.appendChild(alertBox);
        }
    </script>
    @endpush
</x-app-layout> 