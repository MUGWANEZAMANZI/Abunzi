<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
/** This model was use to test seding opt passwords to user in registration, it was stoped beacuase sending sms was expensive and 
 * the vonage logic was replace by twilio
 */
//use App\Models\PhoneVerification;
use App\Notifications\SendSmsVerification;
use App\Services\EmailNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
//use App\Services\SmsNotificationService;
use Illuminate\Mail;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */

    //protected SmsNotificationService $smsNotificationService;
    protected EmailNotificationService $mailNotificationService;
    public function __construct()
    {
        //$this->smsNotificationService = app(SmsNotificationService::class);
        $this->mailNotificationService = app(EmailNotificationService::class);
    }


    public function create(array $input): User
    {
            Validator::make($input, [
            
            'level' => ['nullable', 'string'],
            'level_name' => ['nullable', 'string'],
        ])->validate();

        return tap(User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'identification' => $input['identification'],
            'role' => $input['passcode'] === 'chief2025' || $input['passcode'] === 'cell2025' ? 'chief' : 'citizen',
            'level' => $input['level'] ?? null,
            'levelName' => $input['levelName'] ?? null,
            'password' => Hash::make($input['password']),
        ]), function (User $user) use ($input) {
            if ($input['passcode'] === 'chief2025' || $input['passcode'] === 'cell2025') {
                $this->createTeam($user);
            }

            $this->mailNotificationService->notifyRegistrationMessage($user->name, $user->email);
        });

        


       
    }

    /**
     * Create a personal team for the user (only for Chiefs).
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
