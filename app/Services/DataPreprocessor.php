<?php

namespace App\Services;

use RuntimeException;

class DataPreprocessor
{
    private string $csvFile;
    private array $samples = [];
    private array $labels = [];

    // Common legal terms to preserve
    private const LEGAL_TERMS = [
        'felony', 'misdemeanour', 'imprisonment', 'fine', 'frw',
        'igifungo', 'ihazabu', 'icyaha', 'ingingo', 'amafaranga'
    ];

    public function __construct(string $csvFile)
    {
        $this->csvFile = $csvFile;
    }

    public function process(): array
    {
        if (!file_exists($this->csvFile)) {
            throw new RuntimeException("CSV file not found: {$this->csvFile}");
        }

        $handle = fopen($this->csvFile, 'r');
        
        // Auto-detect delimiter
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $rowIndex = 0;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($rowIndex === 0 || empty($row[0])) {
                $rowIndex++;
                continue;
            }

            // Enhanced text combination with weights
            $combinedText = $this->weightedCombination([
                'name' => $row[0] ?? '', // crime name
                'description' => $row[3] ?? '', // description
                'article' => $row[2] ?? '' // article/category
            ]);

            // Create compound label from classification and punishment
            $label = $this->normalizeLabel($row[1] ?? '') . '|||' . $this->normalizePunishment($row[4] ?? '');

            if (!empty($combinedText) && !empty($label)) {
                $this->samples[] = $combinedText;
                $this->labels[] = $label;
            }

            $rowIndex++;
        }

        fclose($handle);

        if (empty($this->samples)) {
            throw new RuntimeException('No valid data found in CSV file.');
        }

        return [
            'samples' => $this->samples,
            'labels' => $this->labels
        ];
    }

    private function weightedCombination(array $fields): string
    {
        $weights = [
            'name' => 2.0,      // Give more weight to crime name
            'description' => 1.5, // Medium weight to description
            'article' => 1.0     // Base weight for article
        ];

        $combinedText = '';
        foreach ($fields as $field => $text) {
            // Repeat text based on weight to increase its importance
            $repetitions = (int)$weights[$field];
            $cleanedText = $this->cleanText($text);
            $combinedText .= str_repeat($cleanedText . ' ', $repetitions);
        }

        return trim($combinedText);
    }

    private function cleanText(string $text): string
    {
        // Normalize whitespace and convert to lowercase
        $text = mb_strtolower(trim($text));
        
        // Preserve legal terms
        foreach (self::LEGAL_TERMS as $term) {
            $text = str_replace($term, "___${term}___", $text);
        }

        // Remove special characters except letters, numbers, and basic punctuation
        $text = preg_replace('/[^\p{L}\p{N}\s\.\,\!\?]/u', ' ', $text);
        
        // Restore legal terms
        foreach (self::LEGAL_TERMS as $term) {
            $text = str_replace("___${term}___", $term, $text);
        }

        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }

    private function normalizeLabel(string $label): string
    {
        // Normalize article numbers and references
        return preg_replace('/\s+/', ' ', trim($label));
    }

    private function normalizePunishment(string $punishment): string
    {
        // Standardize punishment text
        $punishment = preg_replace('/\s+/', ' ', trim($punishment));
        
        // Normalize currency mentions
        $punishment = preg_replace('/FRW|frw|Frw/', 'FRW', $punishment);
        
        // Normalize number formats
        $punishment = preg_replace('/(\d+)[,\.](\d{3})/', '$1$2', $punishment);
        
        return $punishment;
    }
} 