<!-- User Account Menu -->
<div class="mb-4">
    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2 px-4">{{ __('AKUN SAYA') }}</h3>
    <div class="space-y-1">
        <x-nav-link :href="route('balance.index')" :active="request()->routeIs('balance.*')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('balance.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Saldo Saya') }}
        </x-nav-link>
        <x-nav-link :href="route('deposit.form')" :active="request()->routeIs('deposit.*')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('deposit.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Deposit') }}
        </x-nav-link>
        <x-nav-link :href="route('transfer.form')" :active="request()->routeIs('transfer.*')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('transfer.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Transfer') }}
        </x-nav-link>
        <x-nav-link :href="route('shop.my-purchases')" :active="request()->routeIs('shop.my-purchases')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('shop.my-purchases') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Pembelian Saya') }}
        </x-nav-link>
        <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('cart.index') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Keranjang') }}
            @if(Auth::user()->cart && Auth::user()->cart->total_items > 0)
                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                    {{ Auth::user()->cart->total_items }}
                </span>
            @endif
        </x-nav-link>
        <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('profile.edit') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Pengaturan Profil') }}
        </x-nav-link>
    </div>
</div>

@if(Auth::user()->role === 'admin')
    <!-- Admin Menu -->
    <div class="mb-4">
        <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2 px-4">{{ __('TAKSONOMI') }}</h3>
        <div class="space-y-1">
            <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" 
                class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('categories.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                {{ __('Kategori') }}
            </x-nav-link>
            <x-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.*')" 
                class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('tags.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                {{ __('Tag') }}
            </x-nav-link>
        </div>
    </div>

    <div class="mt-8">
        <div class="px-3 mb-2">
            <h3 class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">
                {{ __('ADMINISTRASI') }}
            </h3>
        </div>
        
        <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Pengaturan Situs') }}
        </x-nav-link>

        <x-nav-link :href="route('galleries.index')" :active="request()->routeIs('galleries.index') || request()->routeIs('galleries.create') || request()->routeIs('galleries.edit')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('galleries.index') || request()->routeIs('galleries.create') || request()->routeIs('galleries.edit') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Manage Galeri') }}
        </x-nav-link>

        <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.index') || request()->routeIs('posts.create') || request()->routeIs('posts.edit')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('posts.index') || request()->routeIs('posts.create') || request()->routeIs('posts.edit') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Manage Blog') }}
        </x-nav-link>

        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit')" 
            class="block w-full px-4 py-2 text-left text-sm rounded-md {{ request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit') ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ __('Manage Shop') }}
        </x-nav-link>
    </div>
@endif 