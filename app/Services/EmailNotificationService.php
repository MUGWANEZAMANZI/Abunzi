<?php 
namespace App\Services;

use App\Mail\GeneralNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    public function notifyDisputeCreated(array $offender, array $user, string $disputeTitle): bool
    {
        $victimSubject = 'Ikirego Cyashyizwe muri Sisitemu';
        $offenderSubject = "Warezwe mu kirego: {$disputeTitle}";

        $victimBody = "Muraho {$user['name']}, ikirego cyanyu '{$disputeTitle}' cyoherejwe mu rwego rw'Abunzi. Muzamenyeshwa igihe kizasomerwa.";
        $offenderBody =  "Muraho {$offender['name']}, Mwarezwe na '{$user['name']}' mu kirego '{$disputeTitle}' muzamenyeshwa igihe kizasomerwa, ikirego cyoherejwe mu rwego rw'Abunzi. Murakoze!";

        $sent1 = $this->sendMail($offender['email'], $offenderSubject, $offenderBody);
        $sent2 = $this->sendMail($user['email'], $victimSubject, $victimBody);
        return $sent1 && $sent2;
    }

   public function notifyDisputeAssigned(array $offender, array $victim, array $justice, string $meetingDate, string $venue, string $title, int $no): bool
{
    $victimSubject = 'Igihe cy’Inama cyatanzwe';
    $offenderSubject = "Warezwe mu rwego rw'Abunzi";
    $subject = "Inama y’ikirego nimero #0{$no}";

    $offenderBody = "Muraho {$offender['name']}, inama y’ikirego nimero #0{$no} gifite umutwe {$title}, uregwamo na {$victim['name']}, izaba kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}. Musabwe kuhagera mbere y'iminota mirongo itatu";
    $victimBody = "Muraho {$victim['name']}, inama y’ikirego nimero #0{$no} gifite {$title} uregamo {$offender['name']}, izaba kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}. Musabwe kuhagera mbere y'iminota mirongo itatu";
    $justiceBody = "Muraho {$justice['name']}, mwashinzwe kuyobora ikirego gifite umutwe:  {$title} cyo ubwunzi hagati ya {$offender['name']} na {$victim['name']} kizaba kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}.";

    $sent1 = $this->sendMail($offender['email'], $offenderSubject, $offenderBody);
    $sent2 = $this->sendMail($victim['email'], $victimSubject, $victimBody);
    $sent3 = $this->sendMail($justice['email'], $subject, $justiceBody);

    return $sent1 && $sent2 && $sent3;
}




    public function notifyDisputePostponed(array $offender, array $victim, array $justice, string $meetingDate, string $newDate, string $cause, string $venue, string $title, int $no): bool
{
    $subject = 'Inama y’ikirego yasubitswe';
    $justiceSubject = "Gusubika ikirego #0{$no} byankunze";
    $subject = "Inama y’ikirego nimero #0{$no} yasubitswe";

    $offenderBody = "Muraho {$offender['name']},inama y’ikirego nimero #0{$no} gifite umutwe {$title}, uregwamo na {$victim['name']}, yari iteganijwe kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}. Yimuriwe kuwa {$newDate}, bitewe {$cause}, Musabwe kugera aho mwari kuzahurira mbere y'iminota mirongo itatu";
    $victimBody = "Muraho  {$victim['name']}, inama y’ikirego nimero #0{$no} gifite umutwe {$title}, uregamo {$offender['name']}, yari iteganijwe kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}. Yimuriwe kuwa {$newDate}, bitewe {$cause}, Musabwe kugera aho mwari kuzahurira mbere y'iminota mirongo itatu";
    $justiceBody = "Muraho {$justice['name']}, Inama mwasubitse ifite umutwe: {$title} hagati ya {$offender['name']} na {$victim['name']} byemeye , bazaburana kuwa {$meetingDate}, kubiro by'Akagari ka: {$venue}.";

    $sent1 = $this->sendMail($offender['email'], $subject, $offenderBody);
    $sent2 = $this->sendMail($victim['email'], $subject, $victimBody);
    $sent3 = $this->sendMail($justice['email'], $justiceSubject, $justiceBody);

    return $sent1 && $sent2 && $sent3;
}



    public function notifyDisputeResolved(array $offender, array $victim, string $resolution, int $no): bool
    {
        $subject = 'Ikirego nimero #{$no} Cyakemutse';
        $body = "Muraho, ikirego cyanyu cyakemutse. Icyemezo: {$resolution}. " . "kura umwanzuro wawe muri sisitemu Abunzi";

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
