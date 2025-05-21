<nav aria-label="breadcrumb" class="py-2">
    <ol class="flex text-gray-500 dark:text-gray-400 text-sm">
        <li class="flex items-center">
            <a href="{{ route('home') }}" class="hover:text-primary-600">Home</a>
            @if(count($breadcrumbs) > 0)
                <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </li>
        @foreach($breadcrumbs as $breadcrumb)
            <li class="flex items-center {{ !$loop->last ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-gray-200' }}">
                @if(!$loop->last)
                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-primary-600">
                        {{ $breadcrumb['title'] }}
                    </a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @else
                    <span>{{ $breadcrumb['title'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav> 