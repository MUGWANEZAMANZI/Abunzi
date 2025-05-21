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
        // Validate input fields
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15', 'min:10', 'unique:users'],
            'identification' => ['required', 'string', 'max:16', 'min:16', 'unique:users'],
            'password' => $this->passwordRules(),
            'passcode' => ['nullable', 'string'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            // Check for chief passcode
            $isChief = isset($input['passcode']) && $input['passcode'] === env('CHIEF_PASSCODE', 'CHIEF2025');
            $role = $isChief ? 'chief' : 'citizen';

            // Create user
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'identification' => $input['identification'],
                'role' => $role,
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($role) {

                // Step 1: Create team for chiefs
                if ($role === 'chief') {
                    $this->createTeam($user);
                }

                /* Step 2: Generate and save phone verification code, not used for now
                $code = rand(100000, 999999);
                PhoneVerification::create([
                    'user_id' => $user->id,
                    'code' => $code,
                ]);

                // Step 3: Send SMS via Vonage
                Notification::route('vonage', $user->phone)
                ->notify(new SendSmsVerification($code, $user->name)); */

                // Step 4: Send SMS via Twilio
                //$this->smsNotificationService->notifyRegistrationMessage($user->phone, $user->name);
                $this->mailNotificationService->notifyRegistrationMessage($user->name, $user->email);

            });
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
