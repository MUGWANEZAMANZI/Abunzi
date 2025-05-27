<div class="container mx-auto p-4">
    @if(empty($disputes))
        <div class="text-center text-gray-500">{{ __('ibirego.title') }}</div>
    @else
        <!-- Tabs -->
        <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
            <button class="inline-block p-4 border-b-2 rounded-t-lg"
                    wire:click="$set('activeTab', 'kirabitse')"
                    :class="{'border-blue-600 text-blue-600': activeTab === 'kirabitse', 'border-transparent hover:border-gray-300': activeTab !== 'kirabitse'}">
                {{ __('ibirego.draft') }}
            </button>
        </li>
        <li class="mr-2">
            <button class="inline-block p-4 border-b-2 rounded-t-lg"
                    wire:click="$set('activeTab', 'cyoherejwe')"
                    :class="{'border-blue-600 text-blue-600': activeTab === 'cyoherejwe', 'border-transparent hover:border-gray-300': activeTab !== 'cyoherejwe'}">
                 {{ __('ibirego.sent') }} 
            </button>
        </li>
        <li class="mr-2">
            <button class="inline-block p-4 border-b-2 rounded-t-lg"
                    wire:click="$set('activeTab', 'kizasomwa')"
                    :class="{'border-blue-600 text-blue-600': activeTab === 'kizasomwa', 'border-transparent hover:border-gray-300': activeTab !== 'kizasomwa'}">
                {{ __('ibirego.assigned')}}
            </button>
        </li>
        <li class="mr-2">
            <button class="inline-block p-4 border-b-2 rounded-t-lg"
                    wire:click="$set('activeTab', 'cyakemutse')"
                    :class="{'border-blue-600 text-blue-600': activeTab === 'cyakemutse', 'border-transparent hover:border-gray-300': activeTab !== 'cyakemutse'}">
                {{ __('ibirego.solved')}}
            </button>
        </li>
    </ul>
    </div>
        <!-- Mobile view -->
        <div class="md:hidden">
            @foreach ($disputes->where('status', $activeTab) as $dispute)
                <div class="mb-4 bg-white rounded-lg shadow p-4 {{ $dispute->status === 'kirabitse' ? 'cursor-pointer' : '' }}"
                    @if($dispute->status === 'kirabitse')
                    wire:click="editDispute({{ $dispute->id }})"
                    @endif
                >
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold">#{{ $dispute->id }}</span>
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($dispute->status === 'kirabitse') bg-yellow-400 text-yellow-800
                            @elseif($dispute->status === 'kizasomwa') bg-blue-400 text-blue-800
                            @elseif($dispute->status === 'cyakemutse') bg-green-400 text-green-800
                            @elseif($dispute->status === 'cyoherejwe') bg-red-400 text-black
                            @endif">
                            {{ $dispute->status }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">{{ __('ibirego.ttitle') }}</span> {{ $dispute->title }}</p>
                        <p><span class="font-semibold">{{ __('ibirego.offender') }}</span> {{ $dispute->offender_name }}</p>
                        <p><span class="font-semibold">{{ __('ibirego.phone') }}</span> {{ $dispute->offender_phone }}</p>
                        <p><span class="font-semibold">{{ __('ibirego.witness') }}</span> {{ $dispute->witness_name }}</p>
                        <p><span class="font-semibold">{{ __('ibirego.location') }}</span> {{ $dispute->location_name }}</p>
                        <p><span class="font-semibold">{{ __('ibirego.date') }}</span> {{ $dispute->created_at->format('Y-m-d H:i') }}</p>
                        <p><span class="font-semibold">{{ __('ibirego.updated') }}</span> {{ $dispute->updated_at->format('Y-m-d H:i') }}</p>
                        @if($dispute->status === 'cyakemutse')
                            <a href="{{ route('dispute.report.download', $dispute->id) }}" class="block text-center text-white bg-blue-500 p-2 rounded-md hover:bg-blue-700 mt-2">Umwanzuro</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop view -->
        <div class="hidden md:block overflow-x-auto">
            <table class="table-auto min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.id') }}</th>
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.ttitle') }}</th>
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.offender') }}</th>
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.phone') }}</th> 
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.witness') }}</th>
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.status') }}</th>
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.location') }}</th>
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.date') }}</th>
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.updated') }}</th>
                        <th class="border border-gray-300 px-4 py-2">{{ __('ibirego.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($disputes->where('status', $activeTab) as $dispute)
                        <tr class="hover:bg-gray-50 {{ $dispute->status === 'kirabitse' ? 'cursor-pointer' : '' }}" 
                            @if($dispute->status === 'kirabitse')
                            wire:click="editDispute({{ $dispute->id }})"
                            @endif
                        >
                            <td class="border border-gray-300 px-4 py-2">{{ $dispute->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $dispute->title }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $dispute->offender_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $dispute->offender_mail }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $dispute->witness_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs
                                    @if($dispute->status === 'kirabitse') bg-yellow-100 text-yellow-800
                                    @elseif($dispute->status === 'kizasomwa') bg-blue-100 text-blue-800
                                    @elseif($dispute->status === 'cyakemutse') bg-green-100 text-green-800
                                    @endif">
                                    {{ $dispute->status }}
                                </span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">{{ $dispute->location_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $dispute->created_at->format('Y-m-d H:i') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $dispute->updated_at->format('Y-m-d H:i') }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if($dispute->status === 'cyakemutse')
                                    <a href="{{ route('dispute.report.download', $dispute->id) }}" class="text-white block bg-blue-500 p-2 rounded-md hover:bg-blue-700">Umwanzuro</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($showIkiregoModal)
        <livewire:ikirego :dispute_id="$selectedDisputeId" />
    @endif
</div>
