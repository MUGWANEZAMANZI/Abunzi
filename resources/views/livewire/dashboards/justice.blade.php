<div>
    <x-app-layout :title="'Justice Dashboard'" :header="__('Justice Dashboard')">
        <x-slot name="title">
            Justice Dashboard
        </x-slot>
        <x-slot name="header">
        </x-slot>

        <div class="mt-4 text-center">
            <h1 class="text-2xl font-bold text-red-500">{{ __('justice-dash.title') }}</h1>
        </div>

        @php
            $hasJusticeAssignments = $TobeSolved->filter(fn($a) => $a->justice !== null)->isNotEmpty();
        @endphp

        @if(!$hasJusticeAssignments)
            <div class="text-center text-lg font-semibold text-black mt-20">
                {{ __('justice-dash.no_disputes') }}
            </div>
        @else
            @foreach($TobeSolved as $assignment)
                @if($assignment->justice)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-1 mb-1 border-l-4 border-blue-600">
                        <div class="flex justify-between items-center mb-1">
                            <div>
                                <h2 class=" font-bold text-gray-900 dark:text-white">
                                    {{ $assignment->dispute->title }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('justice-dash.dispute_number', ['id' => $assignment->dispute->id]) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                    {{ __('justice-dash.status', ['status' => ucfirst($assignment->dispute->status)]) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-1 text-sm text-gray-700 dark:text-gray-300">
                            <div>
                                <h6 class=" text-blue-600 dark:text-blue-400">{{ __('justice-dash.assigned_judge') }}</h6>
                                <p>{{ $assignment->justice->name }} ({{ $assignment->justice->email }})</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-red-600 dark:text-red-400">{{ __('justice-dash.offender') }}</h4>
                                <p>
                                    {{ $assignment->dispute->offender_name }}<br>
                                    <small class="text-xs text-gray-500 dark:text-gray-400">Phone: {{ $assignment->dispute->offender_phone }}</small>
                                </p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-green-600 dark:text-green-400">{{ __('justice-dash.complainant') }}</h4>
                                <p>
                                    {{ $assignment->dispute->citizen->name }} - {{ $assignment->dispute->citizen->email }}<br>
                                    <small class="text-xs text-gray-500 dark:text-gray-400">Phone: {{ $assignment->dispute->citizen->phone }}</small>
                                </p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-purple-600 dark:text-purple-400">{{ __('justice-dash.location') }}</h4>
                                <p>
                                    {{ $assignment->dispute->province }},
                                    {{ $assignment->dispute->district }},
                                    {{ $assignment->dispute->sector }},
                                    {{ $assignment->dispute->cell }},
                                    {{ $assignment->dispute->village }}
                                </p>
                            </div>

                            <div class="col-span-2">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('justice-dash.details') }}</h4>
                                <p class="text-justify">{{ $assignment->dispute->content }}</p>
                            </div>

                            <div class="col-span-2">
                                <h4 class="font-semibold text-orange-600 dark:text-orange-400">{{ __('justice-dash.hearing_date') }}</h4>
                                <p>{{ \Carbon\Carbon::parse($assignment->meeting_time)->format('F d, Y h:i A') }}</p>
                            </div>

                            <div class="col-span-2 flex justify-end">
                                <button wire:click="$set('showPostponeModal', {{ $assignment->id }})"
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-4 py-2 mr-2 rounded shadow">
                                    {{ __('justice-dash.postpone') }}
                                </button>
                                <button wire:click="$set('showModal', {{ $assignment->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow">
                                    {{ __('justice-dash.end_meeting') }}
                                </button>
                            </div>
                        </div>
                        <livewire:a-i-mbaza />

                    </div>

                    @if ($showModal === $assignment->id)
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg w-full max-w-3xl flex flex-col max-h-[90vh]">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                                    {{ __('justice-dash.meeting_concluded', ['title' => $assignment->dispute->title]) }}
                                </h2>
                            </div>

                            <div class="p-4 space-y-4 overflow-y-auto custom-scrollbar flex-grow">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('justice-dash.victim_resolution') }}
                            </label>
                            <textarea wire:model.defer="form.victim_resolution" class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white mt-1"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('justice-dash.offender_resolution') }}
                            </label>
                            <textarea wire:model.defer="form.offender_resolution" class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white mt-1"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('justice-dash.witnesses') }}
                            </label>
                            <input wire:model.defer="form.witnesses" type="text" placeholder="e.g. Musa, Uwera"
                                class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white mt-1"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('justice-dash.attendees') }}
                            </label>
                            <input wire:model.defer="form.attendees" type="text" placeholder="e.g. abaturage, abanyesibo"
                                class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white mt-1"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('justice-dash.justice_resolution') }}
                            </label>
                            <textarea wire:model.defer="form.justice_resolution" class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white mt-1"></textarea>
                        </div>
                                <input wire:model.defer="form.ended_at" type="datetime-local"
                                        class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white mt-1"/>
                                </div>
                                <div class="flex justify-end space-x-3 p-4 border-t border-gray-200 dark:border-gray-700">
                                <button wire:click="$set('showModal', null)"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                    {{ __('justice-dash.cancel') }}
                                </button>
                                <button wire:loading.attr="disabled" wire:click="submitResolution({{ $assignment->id }})"
                                        class="bg-blue-600 disabled:opacity-20 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    {{ __('justice-dash.confirm') }}
                                </button>
                            
                        </div>
                            </div>

                            
                    </div>
                    @endif

                    {{-- Post poned model starts here --}}

                    @if ($showPostponeModal === $assignment->id)
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg w-full max-w-2xl p-6 space-y-4">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">
                                {{ __('justice-dash.postpone_meeting_title', ['title' => $assignment->dispute->title]) }}
                            </h2>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('justice-dash.postpone_reason') }}
                                </label>
                                <textarea wire:model.defer="form.postpone_reason"
                                        class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white mt-1"
                                        placeholder="{{__('justice-dash.placeholder-reason')}}"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('justice-dash.new_hearing_date') }}
                                </label>
                                <input wire:model.defer="form.new_hearing_date" type="datetime-local"
                                    class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white mt-1"/>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button wire:click="$set('showPostponeModal', null)"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                    {{ __('justice-dash.cancel') }}
                                </button>
                                <button wire:loading.attr="disabled" wire:click="submitPostponement({{ $assignment->id }})"
                                        class="bg-yellow-600 disabled:opacity-30 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                                    {{ __('justice-dash.confirm_postpone') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @endif
            @endforeach
        @endif
    </x-app-layout>
</div>
