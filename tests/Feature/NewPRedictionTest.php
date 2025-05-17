<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Services\PredictionService;

class NewPredictionTest extends TestCase
{
    public function test_prediction_with_relevant_input()
    {
        $modelPath = storage_path('app/legal-model.rbx');

        $predictor = new PredictionService($modelPath);

        $input = ['I want to ask about theft cases'];

        $result = $predictor->predict($input);

        // Assert classification is not empty and does not equal the 'no info' response
        $this->assertNotEmpty($result['classification']);

        // For irrelevant input, expect your "no info" response
        $this->assertNotEquals('Ntamakuru mfite ku cyaha umbajije', $result['classification']);

        // You can also assert the confidence is a number between 0 and 100
        $this->assertIsNumeric($result['confidence']);
        $this->assertGreaterThanOrEqual(0, $result['confidence']);
        $this->assertLessThanOrEqual(100, $result['confidence']);
    }

    public function test_prediction_with_irrelevant_input()
    {
        $modelPath = storage_path('app/legal-model.rbx');

        $predictor = new PredictionService($modelPath);

        $input = ['Completely unrelated content without crime info'];

        $result = $predictor->predict($input);

        $this->assertEquals('Ntamakuru mfite ku cyaha umbajije', $result['classification']);
    }
}
