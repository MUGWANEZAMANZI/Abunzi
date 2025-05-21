<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectText;
    public string $messageText;

    /**
     * Create a new message instance.
     *
     * @param string $subjectText
     * @param string $messageText
     */
    public function __construct(string $subjectText, string $messageText)
    {
        $this->subjectText = $subjectText;
        $this->messageText = $messageText;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject($this->subjectText)
                    ->view('emails.general-notification');
    }
}
