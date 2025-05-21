<?php 
namespace App\Services;

use App\Mail\GeneralNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    public function notifyDisputeCreated(string $email, string $name, string $disputeTitle): bool
    {
        $subject = 'Ikirego Cyashyizwe muri Sisitemu';
        $body = "Muraho {$name}, ikirego cyanyu '{$disputeTitle}' cyoherejwe mu rwego rw'Abunzi. Muzamenyeshwa igihe kizasomerwa.";

        return $this->sendMail($email, $subject, $body);
    }

   public function notifyDisputeAssigned(array $offender, array $victim, array $justice, string $meetingDate, string $venue, string $title, int $no): bool
{
    $subject = 'Igihe cy’Inama cyatanzwe';

    $offenderBody = "Muraho {$offender['name']}, inama y’ikirego nimero #0{$no} gifite umutwe {$title}, uregwamo na {$victim['name']}, izaba kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}. Musabwe kuhagera mbere y'iminota mirongo itatu";
    $victimBody = "Muraho {$victim['name']}, inama y’ikirego nimero #0{$no} gifite {$title} uregamo {$offender['name']}, izaba kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}. Musabwe kuhagera mbere y'iminota mirongo itatu";
    $justiceBody = "Muraho {$justice['name']}, mwashinzwe kuyobora ikirego gifite umutwe:  {$title} cyo ubwunzi hagati ya {$offender['name']} na {$victim['name']} kizaba kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}.";

    $sent1 = $this->sendMail($offender['email'], $subject, $offenderBody);
    $sent2 = $this->sendMail($victim['email'], $subject, $victimBody);
    $sent3 = $this->sendMail($justice['email'], $subject, $justiceBody);

    return $sent1 && $sent2 && $sent3;
}


    public function notifyDisputeResolved(array $offender, array $victim, string $resolution, int $no): bool
    {
        $subject = 'Ikirego nimero #{$no} Cyakemutse';
        $body = "Muraho, ikirego cyanyu cyakemutse. Icyemezo: {$resolution}.<br>"."kura umwanzuro wawe muri sisitemu Abunzi";

        return $this->sendMail($offender['email'], $subject, $body)
            && $this->sendMail($victim['email'], $subject, $body);
    }

    public function notifyRegistrationMessage(string $name, string $email): bool
    {
        $subject = 'Kwiyandikisha byakozwe Neza';
        $body = "Muraho {$name}, Murakoze kwiyandikisha muri sisitemu y'Abunzi. " . now();

        return $this->sendMail($email, $subject, $body);
    }

    protected function sendMail(string $to, string $subject, string $body): bool
    {
        try {
            Mail::to($to)->send(new GeneralNotificationMail($subject, $body));
            Log::info("Email sent to {$to}");
            return true;
        } catch (\Exception $e) {
            Log::error('Email send failed', ['error' => $e->getMessage(), 'to' => $to]);
            return false;
        }
    }
}
