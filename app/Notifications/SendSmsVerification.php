<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\VonageMessage;
// Remove the unused Http facade: use Illuminate\Support\Facades\Http;
use App\Models\User; // Make sure to import your User model if passing the object


class SendSmsVerification extends Notification
{
    use Queueable;

    // Modify the constructor to accept the user's name
    public function __construct(protected string $code, protected string $userName) // Added $userName
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['vonage'];
    }


    public function toVonage(object $notifiable): VonageMessage
    {
        // Now you can use $this->userName in the message content
        $messageContent = $this->userName.' Urakoze kwiyandikisha muri Abunzi App, '. '. Kode yawe ni: ' . $this->code;

        return (new VonageMessage)
            ->content($messageContent);
    }

    
}