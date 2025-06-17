<div class="relative z-40">

    <input type="text" class="p-1 rounded-md w-full" placeholder="{{ __('search.placeholder') }}" wire:model.live="searchDispute">

    <button class="text-white p-1 text-xl bg-blue-400 rounded disabled:opacity-20"
        wire:click.prevent="clear()"
        {{ empty($searchDispute) ? 'disabled' : '' }}
    >
        {{ __('search.clear') }}
    </button>

    @if (Str::length($searchDispute) > 0)
        <div class="absolute top-full left-0 w-full bg-white shadow-xl rounded p-2 mt-2 z-50 max-h-60 overflow-y-auto border border-gray-200">
            <h1 class="font-bold mb-2 text-gray-700">{{ __('search.results') }}</h1>
            <ul class="w-full">
                @forelse($users as $user)
                    <li class="text-blue-400 border-b border-gray-100 py-1 hover:bg-gray-50">
                        <a href="search/{{ $user['id'] }}" class="flex items-center justify-between">
                            <span class="w-1/3 text-left bg-blue-500 text-white px-2 py-0.5 rounded">
                                {{ $user['identification'] }}
                            </span>
                            <span class="w-1/3 text-center bg-yellow-400 text-black px-2 py-0.5 rounded">
                                {{ $user['name'] }}
                            </span>
                            <span class="w-1/3 text-right bg-red-500 text-gray-500 px-2 py-0.5 rounded">
                                {{ $user['role'] }}
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="text-gray-500 italic">{{ __('search.no_results') }}</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>
