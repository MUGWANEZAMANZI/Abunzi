<?php

namespace App\Livewire;

use Livewire\Component;
use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Persisters\Filesystem as ModelFilesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIMbaza extends Component
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

  public function askAI()
{
    Log::info('Mbaza AI: Received prompt', ['prompt' => $this->prompt]);

    try {
        if (strlen($this->prompt) < 5) {
            $this->currentPredictionResult = "Injiza ubusobanuro bwuzuye bw'ikibazo cyangwa icyaha.";
            return;
        }

        $sample = [[$this->prompt]];
        $model = PersistentModel::load(new ModelFilesystem(staorage_path('Ai/legal-model.rbx')));
        $dataset = new Unlabeled($sample);
        $predictions = $model->predict($dataset);
        $result = mb_convert_encoding($predictions[0], 'UTF-8', from_encoding: 'UTF-8');

        // Split immediately
        $parts = explode('|||', $result, 2);
        $this->predictionTitle = trim($parts[0] ?? 'Igisubizo');
        $this->predictionContent = trim($parts[1] ?? $result);

        // Set typing animation for content only
        $this->currentPredictionResult = $this->predictionContent;
        $this->visiblePrediction = '';
        $this->predictionTypingIndex = 0;

        $this->dispatch('start-predict-typing');
    } catch (\Throwable $e) {
        Log::error('Mbaza AI: Error during prediction', [
            'error' => $e->getMessage(),
        ]);
        $this->currentPredictionResult = "Habaye ikosa: " . $e->getMessage();
    }
}  
  

    public function render()
    {
        return view('livewire.a-i-mbaza');
    }
}
