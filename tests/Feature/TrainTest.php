<?php

namespace Tests\Feature;

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\StopWordFilter;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Classifiers\SoftmaxClassifier;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Tests\TestCase;

class TrainTest extends TestCase
{
    public function testTrainAndSaveModel()
{
    $csvFile = storage_path('app/dataset-all.csv');
    $handle = fopen($csvFile, 'r');

    // Auto-detect delimiter
    $firstLine = fgets($handle);
    rewind($handle);
    $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

    $samples = [];
    $labels = [];

    $rowIndex = 0;
    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
        // Skip empty rows or header
        if ($rowIndex === 0 || count($row) < 5) {
            $rowIndex++;
            continue;
        }

        // Use full cells, even if they're multiline
        $samples[] = $row[0]. $row[2]. $row[3];
        $labels[] =  $row[1] . '|||' . $row[4];
        $rowIndex++;
    }

    fclose($handle);

    if (empty($samples)) {
        throw new \RuntimeException('Dataset is empty. Check your CSV formatting or column count.');
    }

    $dataset = new Labeled($samples, $labels);

    $vectorizer = new WordCountVectorizer(10000);
    $pipeline = new Pipeline([
        new TextNormalizer(),
        new StopWordFilter(),
        $vectorizer,
    ], new SoftmaxClassifier());

    $model = new PersistentModel($pipeline, new Filesystem(storage_path('app/legal-model.rbx'), true));
    $model->train($dataset);
    $model->save();

    file_put_contents(storage_path('app/vectorizer.rbx'), serialize($vectorizer));

    $this->assertTrue(true);
}
    

}
