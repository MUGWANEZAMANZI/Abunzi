<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $csvFile = storage_path('app/dataset-all.csv');
        $handle = fopen($csvFile, 'r');
    
        // Auto-detect delimiter from first line
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
    
        $rowIndex = 0;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false && $rowIndex < 2) {
            echo "Row $rowIndex: ";
            foreach ($row as $columnIndex => $columnValue) {
                echo "Column $columnIndex: $columnValue. ";
            }
            echo PHP_EOL;
            $rowIndex++;
        }
    
        fclose($handle);
    }
}    