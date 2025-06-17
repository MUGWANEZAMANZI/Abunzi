<div
    x-data="{ open: @entangle('isClosed') }"  {{-- Alpine state linked to Livewire's $isClosed --}}
    x-show="!open"                           {{-- Show the div when 'open' is false --}}
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center p-4"
>
    <div class="bg-gray-500 w-full max-w-4xl p-4 rounded-lg max-h-[90vh] overflow-y-auto">
        <span class="text-red-300 text-xl sm:text-2xl flex justify-end hover:text-red-700">
            <button wire:click="close()" class="p-2">X</button>
        </span>

        <header class="text-center text-white text-lg sm:text-xl md:text-2xl mb-4">{{ __('ikirego.title') }}</header>

        <form wire:submit.prevent="save()" class="space-y-4">
            {{-- ... rest of your form content ... --}}
             <div>
                <label class="block text-white mb-1 text-sm sm:text-base">{{ __('ikirego.tumutwe') }}</label>
                <input type="text" name="title" wire:model="title" class="h-8 sm:h-10 rounded-md w-full px-2 text-sm sm:text-base">
                @error('title') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="text-white text-center text-lg sm:text-xl md:text-2xl mb-2">{{ __('ikirego.site') }}</div>
                 {{-- Location dropdowns --}}
                <div>
                    <div class="mb-4 inline-block mr-1">
                        <label for="province" class="mr-6">{{ __('ikirego.province') }}</label>
                        <select id="province" wire:model.live="selectedProvince" class="inline-block p-2 border rounded">
                            <option value="">-- {{ __('ikirego.cprovince') }} --</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if (!empty($districts))
                    <div class="mb-4 inline-block mr-1">
                        <label for="district">{{ __('ikirego.district') }}</label>
                        <select id="district" wire:model.live="selectedDistrict" class="inline-block p-2 border rounded">
                            <option value="">-- {{ __('ikirego.cdistrict') }} --</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district }}">{{ $district }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if (!empty($sectors))
                    <div class="mb-4 inline-block mr-1">
                        <label for="sector">{{ __('ikirego.sector') }}</label>
                        <select id="sector" wire:model.live="selectedSector" class="inline-block p-2 border rounded">
                            <option value="">-- {{ __('ikirego.sector') }} --</option>
                            @foreach ($sectors as $sector)
                                <option value="{{ $sector }}">{{ $sector }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if (!empty($cells))
                    <div class="mb-4 inline-block mr-1">
                        <label for="cell">{{ __('ikirego.cell') }}</label>
                        <select id="cell" wire:model.live="selectedCell" class="inline-block p-2 border rounded">
                            <option value="">-- {{ __('ikirego.ccell') }} --</option>
                            @foreach ($cells as $cell)
                                <option value="{{ $cell }}">{{ $cell }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if (!empty($villages))
                    <div class="mb-4 inline-block mr-1">
                        <label for="village">{{ __('ikirego.village') }}</label>
                        <select id="village" class="inline-block p-2 border rounded"
                            wire:model.live="selectedVillage">
                            <option value="">-- {{ __('ikirego.cvillage') }} --</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village }}">{{ $village }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                @error('province') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
                @error('district') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
                @error('sector') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
                @error('cell') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
                @error('village') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-white mb-1 text-sm sm:text-base">{{ __('ikirego.offender') }}</label>
                <input type="text" name="offender" wire:model="offender" class="h-8 sm:h-10 rounded-md w-full px-2 text-sm sm:text-base">
                @error('offender') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-white mb-1 text-sm sm:text-base">{{ __('ikirego.mail') }}</label>
                <input type="email" name="offender_mail" wire:model.live="offender_mail" class="h-8 sm:h-10 rounded-md w-full px-2 text-sm sm:text-base" placeholder="Andika imeli y'uwo urega">
                @error('offender_mail') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-white mb-1 text-sm sm:text-base">{{ __('ikirego.description') }}</label>
                <textarea name="content" wire:model="content" class="rounded-lg w-full h-24 sm:h-32 md:h-48 resize-none p-2 text-sm sm:text-base"></textarea>
                @error('content') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-white mb-1 sm:text-base">{{ __('ikirego.witness') }}</label>
                <input type="text" name="witness" wire:model="witness" placeholder="{{ __('ikirego.holder') }}" class="w-full h-8 sm:h-10 px-2 text-sm sm:text-base rounded-md">
                @error('witness') <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-8 mt-6">
                @if($isEditing)
                <button type="button" wire:click="delete" wire:confirm="Uzi neza ko ushaka gusiba iki kirego?" class="w-full sm:w-24 p-2 rounded-md text-base sm:text-lg bg-red-300 hover:bg-red-700 transition-colors">{{ __('ikirego.clear') }}</button>
                @endif
                <button type="button" wire:click="draft"   wire:loading.attr="disabled"  class="w-full disabled:opacity-20 sm:w-24 p-2 rounded-md text-base sm:text-lg bg-yellow-200 hover:bg-yellow-500 transition-colors">{{ __('ikirego.draft') }}</button>
                <button type="submit" wire:loading.attr="disabled"  class="w-full disabled:opacity-20 sm:w-24 p-2 rounded-md text-base sm:text-lg bg-green-200 hover:bg-green-700 transition-colors">{{ __('ikirego.save') }}</button>
            </div>
        </form>
    </div>
</div>
