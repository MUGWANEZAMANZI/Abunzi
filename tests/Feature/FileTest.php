<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FileTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
      dd(ini_get('curl.cainfo'), ini_get('openssl.cafile'));
    }
}
