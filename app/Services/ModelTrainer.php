<?php

namespace App\Services;

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\StopWordFilter;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Classifiers\SoftmaxClassifier;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use RuntimeException;

class ModelTrainer
{
    private const MAX_VOCABULARY = 15000;
    private const MODEL_FILE = 'legal-model.rbx';
    private const VECTORIZER_FILE = 'vectorizer.rbx';

    // Optimized stop words for legal domain
    private const STOP_WORDS = [
        'a', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'for', 'from',
        'has', 'he', 'in', 'is', 'it', 'its', 'of', 'on', 'that', 'the',
        'to', 'was', 'were', 'will', 'with', 'the', 'this', 'these', 'those',
        // Add Kinyarwanda stop words
        'na', 'ku', 'mu', 'no', 'yo', 'ya', 'wa', 'ba', 'cy', 'bya'
    ];

    private array $samples;
    private array $labels;
    private string $storagePath;

    public function __construct(array $samples, array $labels, string $storagePath)
    {
        $this->samples = $samples;
        $this->labels = $labels;
        $this->storagePath = $storagePath;
    }

    public function train(): void
    {
        if (empty($this->samples) || empty($this->labels)) {
            throw new RuntimeException('Cannot train with empty dataset');
        }

        $dataset = new Labeled($this->samples, $this->labels);

        // Create vectorizer with optimized settings for legal text
        $vectorizer = new WordCountVectorizer(
            self::MAX_VOCABULARY,  // Maximum vocabulary size
            2,                     // Minimum document frequency
            0.95                   // Maximum document frequency
        );

        // Create an enhanced pipeline with optimized parameters
        $pipeline = new Pipeline([
            new TextNormalizer(),
            new StopWordFilter(self::STOP_WORDS),
            $vectorizer,
            new TfIdfTransformer()
        ], new SoftmaxClassifier(
            32,         // Batch size - balanced for memory and speed
            null,       // Default optimizer
            0.001,      // L2 penalty - prevent overfitting
            150,        // Increased epochs for better convergence
            1e-4,       // Learning rate - slower but more stable
            100,        // Window size for early stopping
            1e-6       // Tolerance for convergence
        ));

        // Create and train the model with persistence
        $model = new PersistentModel(
            $pipeline,
            new Filesystem($this->storagePath . '/' . self::MODEL_FILE, true)
        );

        // Train the model
        $model->train($dataset);
        $model->save();

        // Save the vectorizer separately for future use
        file_put_contents(
            $this->storagePath . '/' . self::VECTORIZER_FILE,
            serialize($vectorizer)
        );
    }
} 