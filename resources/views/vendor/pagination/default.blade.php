@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center mt-4">
        <ul class="pagination flex list-none space-x-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="px-3 py-1 text-gray-400 border border-gray-300 rounded" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
                       class="px-3 py-1 text-gray-700 border border-gray-300 rounded hover:bg-gray-100">
                        &lsaquo;
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true">
                        <span class="px-3 py-1 text-gray-400">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="px-3 py-1 text-white bg-blue-600 border border-blue-600 rounded">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-1 text-gray-700 border border-gray-300 rounded hover:bg-gray-100">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
                       class="px-3 py-1 text-gray-700 border border-gray-300 rounded hover:bg-gray-100">
                        &rsaquo;
                    </a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="px-3 py-1 text-gray-400 border border-gray-300 rounded" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
