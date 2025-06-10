<div>
    <input type="text" class="p-1 rounded-md" placeholder="Shakisha" wire:model.live.debounce="searchDispute">
    <button class="text-white p-1 text-xl bg-indigo-400 rounded disabled:opacity-20"
        wire:click.prevent="clear()"
        {{ empty($searchDispute) ? 'disabled' : '' }}
        >
        Siba
    </button>
    <div>
         @if (Str::length($searchDispute) > 0) 
            <div class="w-full bg-white shadow rounded p-2 mt-2">
                <h1 class="font-bold mb-2">Results</h1>
                <ul> 
                        <li class="text-blue-400">
                            
                            <a href="search/{{ $users->id }}">
                               <span> {{ $users->name }} </span>
                               <span class="text-gray-500 bg-green-200 rounded ml-5"> {{ $users->role }} </span>
                            </a>
                        </li>
                   


                    </ul>
        {{-- @else
            <div class="text-red-400 mt-2">Nta makuru ahari</div>
        @endif --}}
        @endif
    </div>
</div>
