<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class PhoneTest extends TestCase
{
    /**
     * A basic feature test example.
     */




     public function test_can_send_real_sms()
    {
        $response = Http::withOptions([
            'verify' => 'C:/wamp64/bin/php/php8.3.14/extras/ssl/cacert.pem', // Adjust if needed
        ])->post('https://rest.nexmo.com/sms/json', [
            'api_key'    => env('VONAGE_KEY'),
            'api_secret' => env('VONAGE_SECRET'),
            'to'         => '250787652137',
            'from'       => env('VONAGE_SMS_FROM'),
            'text'       => 'This is a test message from Laravel!',
        ]);

        // Assert response is OK (Vonage usually returns 200 with JSON)
        $this->assertTrue($response->ok(), 'Failed to send SMS');

        // Optional: Dump response to check status
        dump($response->json());
    }

}
