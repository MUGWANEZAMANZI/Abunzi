<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;


class DBTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $tables = Schema::getTables();
        $views = Schema::getViews();
        $columns = Schema::getColumns('users');
        $indexes = Schema::getIndexes('users');
        $foreignKeys = Schema::getForeignKeys('users');
    
    }
}
