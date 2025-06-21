<div class="relative z-40">

    <input type="datetime-local" class="p-1 rounded-md w-1/6 mb-2"
        wire:model.live="searchDateFrom"
        
        {{ $searchDateFrom ? 'disabled' : '' }}
    >
    <label>{{ __('search.date_from') }}</label>

    <input type="datetime-local" class="p-1 rounded-md w-1/6 mb-2"
        wire:model.live="searchDateTo"
        
        {{ $searchDateTo ? 'disabled' : '' }}
    >
    <label>{{ __('search.date_to') }}</label>

    <input type="text" class="p-1 rounded-md w-2/6" placeholder="{{ __('search.placeholder') }}" wire:model.live="searchDispute">

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
                    <li class="border-b border-gray-100 py-2 hover:bg-gray-50">
                        <a href="search/{{ $user->id }}" class="flex items-center justify-between gap-2">
                            <span class="w-1/4 text-left bg-blue-500 text-white px-2 py-0.5 rounded">
                                {{ $user->identification }}
                            </span>
                            <span class="w-1/4 text-center bg-yellow-400 text-black px-2 py-0.5 rounded">
                                {{ $user->name }}
                            </span>
                            <span class="w-1/4 text-right bg-red-500 text-gray-100 px-2 py-0.5 rounded">
                                {{ $user->role }}
                            </span>
                            <span class="w-1/4">
                                <div class="bg-gray-100 rounded p-2 max-h-32 overflow-y-auto">
                                    <span class="block font-semibold text-gray-700 mb-1">{{ __('search.disputes') }}</span>
                                    <ul class="space-y-1">
                                        @forelse ($user->disputes as $dispute)
                                            <li class="text-sm text-gray-800 bg-white rounded px-2 py-1 border border-gray-200 flex justify-between items-center">
                                                <span>{{ $dispute->title }}</span>
                                                <span class="text-xs text-gray-500 ml-2">{{ $dispute->created_at->format('Y-m-d') }}</span>
                                            </li>
                                        @empty
                                            <li class="text-xs text-gray-400 italic">{{ __('search.no_disputes') }}</li>
                                        @endforelse
                                    </ul>
                                </div>
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
