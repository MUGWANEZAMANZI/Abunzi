<div>
    @if ($isClicked)
    <div>
       <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form wire:submit="askAI()">
            <h2 class="text-lg font-bold mb-4">{{ __('Murakaza neza! Nyandikira nkwigishe amategeko mpanabyaha mu Rwanda') }}</h2>
            <x-input class="block mt-1 w-full" type="text" placeholder="{{ __('Andika .....') }}" wire:model="prompt"  />
            @if($isClicked)
            <div>
                {{ $prompt }}
            </div>
            @endif
            <div class="flex justify-end mt-4">
                <x-button type="button" class="ms-4" type="submit">
                    {{ __('Mbaza') }}
                </x-button>
                <x-button type="button" class="ms-4 bg-gray-500 hover:bg-gray-700" wire:click="close()">
                    {{ __('Funga') }}
                </x-button>
            </div>
            </form>
        </div>
    </div>
    </div>
    @endif
   
    <button wire:click="showModal()" class="fixed right-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Ask AI 
    </button>
</div>

