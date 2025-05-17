<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

/**
 * Service for sending SMS notifications related to dispute resolution.
 */
class SmsNotificationService
{
    protected Client $client;
    protected string $fromNumber;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.auth_token')
        );

        $this->fromNumber = config('services.twilio.from_number');
    }

    /**
     * Send notification when a dispute is created.
     */
    public function notifyDisputeCreated(string $userPhone, string $userName, string $disputeTitle): bool
    {
        $message = "Muraho {$userName}, ikirego cyanyu '{$disputeTitle}' cyoherejwe mu rwego rw'Abunzi. Muzamenyeshwa igihe kizasomerwa. Murakoze.";
        return $this->sendSms($userPhone, $message);
    }

    /**
     * Notify both parties about dispute assignment and hearing details.
     */
    public function notifyDisputeAssigned(array $offender, array $victim, string $meetingDate, string $venue): bool
    {
        $offenderMessage = "Muraho {$offender['name']}, ikirego cyanyu cyashyikirijwe Abunzi. Inama izakorwa kuwa {$meetingDate} ahabera: {$venue}. Murakoze.";
        $victimMessage   = "Muraho {$victim['name']}, ikirego cyanyu cyatanzwe. Inama izakorwa kuwa {$meetingDate} ahabera: {$venue}. Murakoze.";

        $sent1 = $this->sendSms($offender['phone'], $offenderMessage);
        $sent2 = $this->sendSms($victim['phone'], $victimMessage);

        return $sent1 && $sent2;
    }

    /**
     * Notify both parties about dispute resolution.
     */
    public function notifyDisputeResolved(array $offender, array $victim, string $resolution): bool
    {
        $message = "Muraho, ikirego cyanyu cyakemutse. Icyemezo: {$resolution}. Wakura raporo irambuye muri sisitemu.";

        $sent1 = $this->sendSms($offender['phone'], $message);
        $sent2 = $this->sendSms($victim['phone'], $message);

        return $sent1 && $sent2;
    }





    /** Registration confirmation message */
    public function notifyRegistrationMessage($name, $phone) : bool{
        $message = "Muraho neza {$name}. Murakoze kwiyandikisha muri sisitemu y'abunzi yo gukemura ibibazo by'abaturage \n". date('m-d-Y-His');
        return $this->sendSms($phone, $message);

    }

    /**
     * Generic SMS sending with error handling and logging.
     */
    protected function sendSms(string $to, string $message): bool
    {
        try {
            $this->client->messages->create($to, [
                'from' => $this->fromNumber,
                'body' => $message,
            ]);

            Log::info('SMS sent successfully', [
                'to' => $to,
                'message' => $message,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS', [
                'error' => $e->getMessage(),
                'to' => $to,
                'message' => $message,
            ]);

            return false;
        }
    }
}
