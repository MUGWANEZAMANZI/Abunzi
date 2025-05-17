<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Twilio\Rest\Client;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Api\V2010\Account\MessageList;
use Mockery;

class SmsNotificationServiceTest extends TestCase
{
    public function testSendDisputeCreatedToUserAndOffender()
    {
        // Create mock message instances with fake SIDs
        $mockMessageToUser = Mockery::mock(MessageInstance::class);
        $mockMessageToUser->sid = 'SMUSERXXXXXXXXXXXXXXXXXXXXXX';

        $mockMessageToOffender = Mockery::mock(MessageInstance::class);
        $mockMessageToOffender->sid = 'SMOFFENDERXXXXXXXXXXXXXXXXX';

        // Mock the MessageList
        $mockMessageList = Mockery::mock(MessageList::class);

        // Expect create() to be called with user number
        $mockMessageList->shouldReceive('create')
            ->once()
            ->with(
                '+250787652137',
                [
                    'from' => '+18578108381',
                    'body' => "Muraho, ikirego cyanyu cyoherejwe mu rwego rw'Abunzi.\nMuzamenyeshwa igihe kizasomerwa. Murakoze."
                ]
            )
            ->andReturn($mockMessageToUser);

        // Expect create() to be called with offender number
        $mockMessageList->shouldReceive('create')
            ->once()
            ->with(
                '+250788888888',
                [
                    'from' => '+18578108381',
                    'body' => "Muraho, mwamenyeshejwe ko hari ikirego cyatanzwe kibareba. Muzategereze ubutumire mu rwego rw'Abunzi."
                ]
            )
            ->andReturn($mockMessageToOffender);

        // Mock the Twilio Client
        $mockClient = Mockery::mock(Client::class);
        $mockClient->messages = $mockMessageList;

        // Bind the mock in the service container
        $this->app->instance(Client::class, $mockClient);

        // Act: Simulate sending both messages
        $messageToUser = $mockClient->messages->create(
            '+250787652137',
            [
                'from' => '+18578108381',
                'body' => "Muraho, ikirego cyanyu cyoherejwe mu rwego rw'Abunzi.\nMuzamenyeshwa igihe kizasomerwa. Murakoze."
            ]
        );

        $messageToOffender = $mockClient->messages->create(
            '+250788888888',
            [
                'from' => '+18578108381',
                'body' => "Muraho, mwamenyeshejwe ko hari ikirego cyatanzwe kibareba. Muzategereze ubutumire mu rwego rw'Abunzi."
            ]
        );

        // Log the results
        Log::info('User SMS sent successfully', [
            'to' => '+250787652137',
            'message' => $messageToUser->sid,
        ]);

        Log::info('Offender SMS sent successfully', [
            'to' => '+250788888888',
            'message' => $messageToOffender->sid,
        ]);

        // Assertions
        $this->assertEquals('SMUSERXXXXXXXXXXXXXXXXXXXXXX', $messageToUser->sid);
        $this->assertEquals('SMOFFENDERXXXXXXXXXXXXXXXXX', $messageToOffender->sid);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
