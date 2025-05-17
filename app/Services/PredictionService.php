<?php
namespace App\Services;

use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Datasets\Unlabeled;
use RuntimeException;

class PredictionService
{
    private PersistentModel $model;
    private array $legalTerms;

    // Common legal terms in both English and Kinyarwanda
    private const LEGAL_TERMS = [
        'murder' => ['kwica', 'ubwicanyi'],
        'theft' => ['kwiba', 'ubujura'],
        'assault' => ['gukubita', 'guhohotera'],
        'rape' => ['gusambanya', 'gufata kungufu'],
        'fraud' => ['uburiganya', 'kuriganya'],
        'imprisonment' => ['igifungo', 'gufungwa'],
        'fine' => ['ihazabu', 'gucibwa']
    ];

    // Threshold for confidence below which we consider no info
    private const CONFIDENCE_THRESHOLD = 0.1;  // Adjust as needed

    public function __construct(string $modelPath)
    {
        if (!file_exists($modelPath)) {
            throw new RuntimeException("Model file not found: {$modelPath}");
        }
        
        $this->model = PersistentModel::load(new Filesystem($modelPath));
        $this->legalTerms = self::LEGAL_TERMS;
    }

    public function predict(array $input): array
    {
        try {
            $processedInput = $this->formatInput($input);

            $dataset = new Unlabeled([$processedInput]);
            $predictions = $this->model->predict($dataset);

            // Try getting probabilities/confidence
            try {
                $probabilities = $this->model->proba($dataset);
                $confidence = max($probabilities[0] ?? [0]);
            } catch (\Exception $e) {
                $confidence = 1.0; // Default confidence if unavailable
            }

            $prediction = $predictions[0] ?? '|||';
            [$classification, $punishment] = array_pad(explode('|||', $prediction), 2, '');

            if ($confidence < self::CONFIDENCE_THRESHOLD || empty(trim($classification))) {
                return [
                    'classification' => 'Ntamakuru mfite ku cyaha umbajije',
                    'punishment' => '',
                    'confidence' => round($confidence * 100, 2),
                    'alternative_terms' => [],
                    'processed_input' => $processedInput
                ];
            }

            return [
                'classification' => trim($classification) ?: 'Unknown',
                'punishment' => trim($punishment) ?: 'Unable to determine',
                'confidence' => round($confidence * 100, 2),
                'alternative_terms' => $this->findRelatedTerms($processedInput),
                'processed_input' => $processedInput
            ];
        } catch (\Exception $e) {
            return [
                'classification' => 'Ntamakuru mfite ku cyaha umbajije',
                'punishment' => '',
                'confidence' => 0,
                'error' => $e->getMessage(),
                'alternative_terms' => [],
                'processed_input' => $input[0] ?? ''
            ];
        }
    }

    private function formatInput(array $input): string
    {
        $text = implode(' ', array_filter($input));
        $text = mb_strtolower(trim($text));

        // Preserve legal terms spacing for recognition
        foreach ($this->legalTerms as $english => $kinyarwanda) {
            $text = str_ireplace($english, " $english ", $text);
            foreach ($kinyarwanda as $term) {
                $text = str_ireplace($term, " $term ", $text);
            }
        }

        // Remove unwanted characters, normalize spaces
        $text = preg_replace('/[^\p{L}\p{N}\s\.\,\!\?]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    private function findRelatedTerms(string $input): array
    {
        $relatedTerms = [];

        foreach ($this->legalTerms as $english => $kinyarwanda) {
            if (stripos($input, $english) !== false) {
                $relatedTerms[$english] = $kinyarwanda;
            }
            foreach ($kinyarwanda as $term) {
                if (stripos($input, $term) !== false) {
                    $relatedTerms[$term] = [$english];
                }
            }
        }

        return $relatedTerms;
    }
}
