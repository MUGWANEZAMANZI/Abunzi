<?php

namespace Tests\Feature;

use Rubix\ML\Extractors\CSV;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\StopWordFilter;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Classifiers\LogisticRegression;
use Rubix\ML\Classifiers\Softmax;
use Rubix\ML\Persisters\Filesystem;
use Tests\TestCase;

class MbazaModelTest extends TestCase
{
    public function testTrainAndSaveModel()
    {
        $path = storage_path('app/dataset-kiny.csv'); // Adjust the path to your dataset

        $dataset = Labeled::fromIterator(new CSV($path, true, ','));

        $samples = [];
        $labels = [];

        foreach ($dataset->samples() as $i => $sample) {
            $text = "{$sample[1]} {$sample[2]} {$sample[4]}";  // Adjust columns as needed
            $samples[] = [$text];
            $labels[] = $dataset->labels()[$i];
        }

        $dataset = new Labeled($samples, $labels);

        $transformers = [
            new TextNormalizer(),
            new StopWordFilter(),
            new WordCountVectorizer(1000),
        ];

        foreach ($transformers as $transformer) {
            $dataset->apply($transformer);
        }

        // Use Softmax for multi-class classification
        $estimator = new Softmax(); // Changed from LogisticRegression to Softmax for multi-class
        $estimator->train($dataset);

        $persister = new Filesystem(storage_path('app/model.rbx'), true);
        $persister->save($estimator);

        $this->assertTrue(true); // Add assertions as needed
    }
    



}
