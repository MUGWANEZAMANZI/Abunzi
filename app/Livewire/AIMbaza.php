<?php

namespace App\Livewire;

use Livewire\Component;
use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Persisters\Filesystem as ModelFilesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Aimbaza extends Component
{
    public bool $isClicked = false;
    public string $prompt = '';

    public string $fullGreeting;
    public string $visibleGreeting = '';
    public int $typingIndex = 0;

    public string $currentPredictionResult = '';
    public string $visiblePrediction = '';
    public int $predictionTypingIndex = 0;

    public array $pastPredictions = [];
    public string $predictionTitle = '';
    public string $predictionContent = '';



    public function mount(){
        $this->fullGreeting = __('ai.welcome');
    }


    public function showModal()
    {
        $this->isClicked = true;
        $this->visibleGreeting = '';
        $this->typingIndex = 0;
        $this->dispatch('start-typing');
    }

    #[\Livewire\Attributes\On('typeNextCharacter')]
    public function typeNextCharacter()
    {
        if ($this->typingIndex < mb_strlen($this->fullGreeting)) {
            $this->visibleGreeting .= mb_substr($this->fullGreeting, $this->typingIndex, 1);
            $this->typingIndex++;
        }
    }

#[\Livewire\Attributes\On('typePredictionCharacter')]
public function typePredictionCharacter()
{
    if ($this->predictionTypingIndex < mb_strlen($this->currentPredictionResult)) {
        $this->visiblePrediction .= mb_substr($this->currentPredictionResult, $this->predictionTypingIndex, 1);
        $this->predictionTypingIndex++;
    } elseif ($this->currentPredictionResult !== '') {
        // Add to past only once
        array_unshift($this->pastPredictions, [
            'title' => $this->predictionTitle,
            'content' => $this->predictionContent,
        ]);

        $this->currentPredictionResult = ''; // prevent re-processing
    }
}




    public function close()
    {
        $this->isClicked = false;
        $this->prompt = '';
        $this->visibleGreeting = '';
        $this->typingIndex = 0;
        $this->currentPredictionResult = '';
        $this->visiblePrediction = '';
        $this->predictionTypingIndex = 0;
        $this->pastPredictions = [];
    }

        public function askAI(){
        Log::info('Mbaza AI: Received prompt', ['prompt' => $this->prompt]);

        try {
            // Step 1: Validate input
            if (strlen($this->prompt) < 5) {
                $this->currentPredictionResult = "Injiza ubusobanuro bwuzuye bw'ikibazo cyangwa icyaha.";
                return;
        }

        // Step 2: Load model and prepare dataset
        $sample = [$this->prompt]; // Single prompt input
        $model = PersistentModel::load(new ModelFilesystem(storage_path('app/legal-model.rbx')));
        $dataset = new Unlabeled($sample);

        // Step 3: Run probability prediction
        $probabilities = $model->proba($dataset);

        // Step 4: Use only the first prediction result
        $firstProbs = $probabilities[0] ?? [];

        arsort($firstProbs); // Sort by highest confidence
        $topClass = key($firstProbs); // Most likely prediction
        $topProbability = reset($firstProbs); // Its probability score

        // Step 5: Log top result only
        Log::info('Mbaza AI: Top Prediction (first sample only)', [
            'top_class' => $topClass,
            'confidence_percent' => $topProbability * 100
        ]);

        // Step 6: If confidence is too low (<40%), show "no answer"
        if ($topProbability < 0.2) {
            $this->predictionTitle = '';
            $this->predictionContent = '';
            $this->currentPredictionResult = "Ntagisubizo cyabonetse. Mwongere mugerageze.";

            $this->predictionTypingIndex = 0;
            $this->dispatch('start-predict-typing');
            array_unshift($this->pastPredictions, [
            'title' => "Ntagisubizo cyabonetse",
            'content' => "Ntagisubizo cyabonetse. Mwongere mugerageze.",
        ]);
            Log::info('Mbaza AI: Low confidence, no answer provided');
            Log::info('Result: ' . $this->currentPredictionResult);
            return;
        }

        // Step 7: Extract title and content from prediction
        $parts = explode('|||', $topClass, 2);
        $this->predictionTitle = trim($parts[0] ?? 'Igisubizo');
        $this->predictionContent = trim($parts[1] ?? $topClass);

        // Step 8: Prepare for typing animation
        $this->currentPredictionResult = $this->predictionContent;
        $this->visiblePrediction = '';
        $this->predictionTypingIndex = 0;
        $this->dispatch('start-predict-typing');

        // Step 9: Store in past predictions
        array_unshift($this->pastPredictions, [
            'title' => $this->predictionTitle,
            'content' => $this->predictionContent,
        ]);
    } catch (\Throwable $e) {
        Log::error('Mbaza AI: Error during prediction', [
            'error' => $e->getMessage(),
        ]);
        $this->currentPredictionResult = "Habaye ikosa: " . $e->getMessage();
    }
}

     
    public function render()
    {
        return view('livewire.aimbaza');
    }
}
