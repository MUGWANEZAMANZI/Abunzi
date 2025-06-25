<?php

namespace App\Livewire;

use Livewire\Component;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Log;

class RegistrationForm extends Component
{
    public $name, $email, $password, $password_confirmation;
    public $identification, $phone, $passcode;
    public $level, $levelName;

    public function updatedPasscode()
    {
        // Dynamically assign level based on passcode

        Log::info("value is:", [$this->passcode]);
        if ($this->passcode === 'chief2025') {
            $this->level = 'sector';
        } elseif ($this->passcode === 'cell2025') {
            $this->level = 'cell';
        } else {
            $this->level = null;
            $this->levelName = null;
        }
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
            'identification' => 'required|string|size:16|unique:users',
            'phone' => 'required|string|min:10|max:15|unique:users',
            'passcode' => 'nullable|string',
            'level' => 'nullable|string|in:cell,sector',
            'levelName' => 'nullable|string|max:255',
        ]);

        $input = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'identification' => $this->identification,
            'password' => $this->password,
            'passcode' => $this->passcode,
            'level' => $this->level,
            'levelName' => $this->levelName,
        ];

        (new CreateNewUser)->create($input);

        return redirect()->route('login');
    }



    public function render()
    {
        return view('livewire.registration-form');
    }
}
