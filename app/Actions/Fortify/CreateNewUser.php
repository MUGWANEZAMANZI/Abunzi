<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Validate input fields
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'identification' => ['required', 'string', 'max:16', 'min:16', 'unique:users'],
            'password' => $this->passwordRules(),
            'passcode' => ['nullable', 'string'], 
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            // Check if the user is registering with a valid passcode for Chief role
            $isChief = isset($input['passcode']) && $input['passcode'] === env('CHIEF_PASSCODE', 'CHIEF2025');
            
            // Default role is 'citizen'; if chief passcode is provided, set role to 'chief'
            $role = $isChief ? 'chief' : 'citizen';

            // Create the user
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'identification' => $input['identification'],
                'role' => $role,
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($role) {
                // If user is a Chief, allow them to create a team
                if ($role === 'chief') {
                    $this->createTeam($user);
                }
            });
        });
    }

    /**
     * Create a personal team for the user (only for Chiefs).
     */
    protected function createTeam(User $user): void
    {
        // Create a personal team for the Chief
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
