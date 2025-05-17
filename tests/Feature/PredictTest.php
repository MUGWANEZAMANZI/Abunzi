<?php

namespace Tests\Feature;

use App\Services\PredictionService;
use Tests\TestCase;

class PredictTest extends TestCase
{
    private PredictionService $predictionService;
    private array $warnings = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->predictionService = new PredictionService(
            storage_path('app/legal-model.rbx')
        );
        $this->warnings = [];
    }

    /**
     * Test cases for different types of crimes
     */
    public function testPredictCases(): void
    {
        $testCases = $this->getCrimeCases();
        $hasFailures = false;

        foreach ($testCases as $caseName => [$description, $expectedTerms]) {
            echo "\n=== Testing: $caseName ===";
            
            // Make prediction
            $result = $this->predictionService->predict([$description]);

            // Output formatted results
            echo "\nInput: " . $description;
            echo "\nProcessed: " . ($result['processed_input'] ?? 'N/A');
            echo "\nClassification: " . $result['classification'];
            echo "\nPunishment: " . $result['punishment'];
            echo "\nConfidence: " . $result['confidence'] . "%";
            
            if (isset($result['error'])) {
                echo "\nError: " . $result['error'];
                $this->warnings[] = "Error in prediction: " . $result['error'];
                continue;
            }
            
            if (!empty($result['alternative_terms'])) {
                echo "\nRelated Terms:";
                foreach ($result['alternative_terms'] as $term => $translations) {
                    echo "\n  - $term: " . implode(', ', $translations);
                }
            }
            echo "\n";

            // Check if we got an article number instead of a description
            if (preg_match('/^(ingingo|\d+)/', $result['classification'])) {
                $this->warnings[] = "Got article reference instead of description for: $description";
                $hasFailures = true;
                continue;
            }

            // Check for expected terms
            $combinedText = strtolower($result['classification'] . ' ' . $result['punishment']);
            $missingTerms = [];
            foreach ($expectedTerms as $term) {
                if (!str_contains($combinedText, strtolower($term))) {
                    $missingTerms[] = $term;
                }
            }

            if (!empty($missingTerms)) {
                $this->warnings[] = "Missing terms for '$description': " . implode(', ', $missingTerms);
                $hasFailures = true;
            }

            // Basic validation
            if ($result['classification'] === 'Unknown' || $result['punishment'] === 'Unable to determine') {
                $this->warnings[] = "Got unknown classification or punishment for: $description";
                $hasFailures = true;
            }
        }

        // Output all warnings at the end
        if (!empty($this->warnings)) {
            echo "\n\nWarnings:";
            foreach ($this->warnings as $warning) {
                echo "\n- $warning";
            }
        }

        // If we had any failures, fail the test
        $this->assertFalse($hasFailures, "Some predictions did not meet expectations. See warnings above.");
    }

    private function getCrimeCases(): array
    {
        return [
            'Murder case' => [
                'Intentionally killing another person with premeditation',
                ['murder', 'life', 'imprisonment']
            ],
            'Theft case' => [
                'Breaking into a house at night and stealing valuable items',
                ['theft', 'imprisonment', 'fine']
            ],
            'Sexual assault case' => [
                'Sexual assault on a minor under the age of 14',
                ['sexual', 'assault', 'imprisonment']
            ],
            'Fraud case' => [
                'Using deception to obtain money from multiple victims',
                ['fraud', 'fine']
            ],
            'Domestic violence case' => [
                'Physical assault against spouse in their home',
                ['assault', 'domestic', 'imprisonment']
            ],
            'Kinyarwanda case' => [
                'Kwiba ibintu mu nzu nijoro hakoreshejwe kiboko',
                ['theft', 'night', 'violence']
            ],
            'Mixed language case' => [
                'Gukora igikorwa cyo kwica umuntu intentionally',
                ['murder', 'intentional']
            ]
        ];
    }

    public function testEdgeCases(): void
    {
        $edgeCases = [
            'Empty input' => [''],
            'Numbers only' => ['123456'],
            'Special characters' => ['!@#$%^&*()'],
            'Very long input' => [str_repeat('test case ', 100)],
            'Mixed languages with numbers' => ['Kwiba 5000 FRW mu nzu ya bank']
        ];

        foreach ($edgeCases as $caseName => $input) {
            echo "\n=== Testing $caseName ===\n";
            $result = $this->predictionService->predict($input);
            
            // For edge cases, we expect either an error or a low confidence prediction
            if (isset($result['error'])) {
                echo "Got expected error response: " . $result['error'] . "\n";
                $this->assertEquals('Unknown', $result['classification']);
                $this->assertEquals('Unable to determine', $result['punishment']);
                $this->assertEquals(0, $result['confidence']);
            } else {
                echo "Got prediction with {$result['confidence']}% confidence\n";
                echo "Classification: " . $result['classification'] . "\n";
                echo "Punishment: " . $result['punishment'] . "\n";
                
                // For edge cases, we should get either Unknown classification or very low confidence
                $this->assertTrue(
                    $result['classification'] === 'Unknown' || 
                    $result['confidence'] < 30,
                    "Edge case '$caseName' should have low confidence or unknown classification"
                );
            }
        }
    }
}
