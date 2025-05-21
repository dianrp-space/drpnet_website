<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Welcome Slider') }}
            </h2>
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Back to Settings
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Display success message if available -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Add New Slide</h3>
                    
                    <form action="{{ route('admin.settings.slides.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="title" :value="__('Slide Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="active" :value="__('Status')" />
                                <select id="active" name="active" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Slide Image')" />
                            <input type="file" id="image" name="image" accept="image/*" required class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                                dark:file:bg-indigo-900 dark:file:text-indigo-300
                                dark:hover:file:bg-indigo-800">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                {{ __('Add Slide') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Slides -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Current Slides</h3>
                    
                    @if($slides->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No slides have been added yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($slides as $slide)
                                <div class="border rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-700">
                                    <img src="{{ asset('storage/' . $slide->image_path) }}" alt="{{ $slide->title }}" class="w-full h-48 object-cover">
                                    
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium">{{ $slide->title ?? 'Untitled Slide' }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $slide->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $slide->active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        
                                        @if($slide->description)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ Str::limit($slide->description, 100) }}</p>
                                        @endif
                                        
                                        <div class="flex justify-between mt-2">
                                            <button onclick="openEditModal({{ $slide->id }})" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                                Edit
                                            </button>
                                            
                                            <form action="{{ route('admin.settings.slides.delete', $slide) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slide?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Edit Modal for this slide (hidden by default) -->
                                <div id="edit-modal-{{ $slide->id }}" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50 flex items-center justify-center">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full mx-4 p-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium">Edit Slide</h3>
                                            <button onclick="closeEditModal({{ $slide->id }})" class="text-gray-500 hover:text-gray-700">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <form action="{{ route('admin.settings.slides.update', $slide) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="mb-4">
                                                <x-input-label for="edit-title-{{ $slide->id }}" :value="__('Slide Title')" />
                                                <x-text-input id="edit-title-{{ $slide->id }}" name="title" type="text" value="{{ $slide->title }}" class="mt-1 block w-full" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-input-label for="edit-description-{{ $slide->id }}" :value="__('Description')" />
                                                <textarea id="edit-description-{{ $slide->id }}" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">{{ $slide->description }}</textarea>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-input-label for="edit-active-{{ $slide->id }}" :value="__('Status')" />
                                                <select id="edit-active-{{ $slide->id }}" name="active" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                                    <option value="1" {{ $slide->active ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !$slide->active ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-input-label for="edit-order-{{ $slide->id }}" :value="__('Order')" />
                                                <x-text-input id="edit-order-{{ $slide->id }}" name="order" type="number" value="{{ $slide->order }}" min="1" class="mt-1 block w-full" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-input-label for="edit-image-{{ $slide->id }}" :value="__('Slide Image')" />
                                                <div class="mt-2 mb-2">
                                                    <img src="{{ asset('storage/' . $slide->image_path) }}" alt="{{ $slide->title }}" class="h-32 object-cover rounded">
                                                </div>
                                                <input type="file" id="edit-image-{{ $slide->id }}" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                                                    file:mr-4 file:py-2 file:px-4
                                                    file:rounded-md file:border-0
                                                    file:text-sm file:font-semibold
                                                    file:bg-indigo-50 file:text-indigo-700
                                                    hover:file:bg-indigo-100
                                                    dark:file:bg-indigo-900 dark:file:text-indigo-300
                                                    dark:hover:file:bg-indigo-800">
                                            </div>
                                            
                                            <div class="flex justify-end">
                                                <x-secondary-button type="button" onclick="closeEditModal({{ $slide->id }})" class="mr-2">
                                                    Cancel
                                                </x-secondary-button>
                                                <x-primary-button>
                                                    Save Changes
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(slideId) {
            document.getElementById('edit-modal-' + slideId).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        
        function closeEditModal(slideId) {
            document.getElementById('edit-modal-' + slideId).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
    @endpush
</x-app-layout> 