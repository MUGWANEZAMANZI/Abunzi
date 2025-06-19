<div>
<div>
    @if ($isClicked)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto space-y-4">

                <!-- Greeting -->
                <div class="text-green-700 font-semibold mb-4 text-lg" 
                    x-data="{ started: false }"
                    x-init="
                        window.addEventListener('start-typing', () => {
                            if (!started) {
                                started = true;
                                setInterval(() => Livewire.dispatch('typeNextCharacter'), 40);
                            }
                        });
                    ">
                    {{ $visibleGreeting }}
                </div>

                <!-- Prompt Form -->
                <form wire:submit.prevent="askAI">
                    <x-input class="block mt-1 w-full" type="text"
                        placeholder="Andika icyaha cyakozwe..."
                        wire:model.debounce.600ms="prompt" />

                    <div class="flex justify-end mt-4 space-x-2">
                        <x-button type="submit" class="bg-green-600 hover:bg-green-700 text-white">
                            {{ __('ai.ask') }}
                        </x-button>
                        <x-button type="button" class="bg-gray-500 hover:bg-gray-700 text-white" wire:click="close">
                            {{ __('ai.close') }}
                        </x-button>
                    </div>
                </form>

                <!-- Animated Prediction -->
                @if ($currentPredictionResult)
                    <div class="animate-fade-in mt-4 p-4 bg-green-50 border-l-4 border-green-600 rounded shadow" 
                        x-data="{ started: false }"
                        x-init="
                            window.addEventListener('start-predict-typing', () => {
                                if (!started) {
                                    started = true;
                                    setInterval(() => Livewire.dispatch('typePredictionCharacter'), 35);
                                }
                            });
                        ">
                        <h3 class="text-green-700 font-bold mb-2">{{ __('ai.new') }}</h3>
                        <p class="text-gray-800 whitespace-pre-line">{{ $visiblePrediction }}</p>
                    </div>
                @endif

                <!-- Past Predictions -->
                @foreach ($pastPredictions as $index => $entry)
                    <div class="p-4 bg-gray-100 rounded-lg shadow-sm border border-gray-300">
                        <h4 class="text-sm text-gray-600 font-semibold mb-1">{{ __('ai.article') . $entry['title'] }}</h4>
                        <div class="text-gray-800 whitespace-pre-line text-sm">{{ $entry['content'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Floating Button -->
    <button wire:click="showModal"
        class="fixed bottom-6 right-6 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full shadow-lg">
        Mbaza AI
    </button>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</div>
