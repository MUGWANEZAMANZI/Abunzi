<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Rest\Client;

class SendDisputeSms extends Command
{
    protected $signature = 'sms:send-dispute';
    protected $description = 'Send dispute SMS to user and offender using Twilio';

    public function handle()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.from_number'); // e.g. +18578108381

        $twilio = new Client($sid, $token);

        // User SMS
        $userNumber = '+250787652137';
        $userBody = "Muraho, ikirego cyanyu cyoherejwe mu rwego rw'Abunzi.\nMuzamenyeshwa igihe kizasomerwa. Murakoze.";

        // Offender SMS
        $offenderNumber = '+250787652137';
        $offenderBody = "Muraho, mwamenyeshejwe ko hari ikirego cyatanzwe kibareba. Muzategereze ubutumire mu rwego rw'Abunzi.";

        try {
            $messageToUser = $twilio->messages->create($userNumber, [
                'from' => $from,
                'body' => $userBody
            ]);

            $messageToOffender = $twilio->messages->create($offenderNumber, [
                'from' => $from,
                'body' => $offenderBody
            ]);

            $this->info("User SMS sent: " . $messageToUser->sid);
            $this->info("Offender SMS sent: " . $messageToOffender->sid);

        } catch (\Exception $e) {
            $this->error("Failed to send SMS: " . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
