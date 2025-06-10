<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\User;
use App\Models\Dispute;
use Illuminate\Support\Facades\Log;

class Search extends Component
{
    #[Validate('required')]
    public String $searchDispute = '';
    public $disputes = [];
    public $users = [];


    public function updatedSearchDispute($value)
    {
        $this->reset("users");

        $this->validate();

        $users = User::where("identification", "like", "{$value}%")->get();

        if(!$users){
            Log::info("No user found for search: {$value}");
            $this->disputes = [];
            return;
        }

        Log::info("User found: {$users->id} - {$users->name}");

        // $disputes = Dispute::where("citizen_id", $user->id)->get();

        // if($disputes->isEmpty()) {
        //     Log::info("No disputes found for user ID: {$user->id}");
        // } else {
        //     Log::info("Found " . $disputes->count() . " disputes for user ID: {$user->id}");
        // }

        // $this->disputes = $disputes;

        //REturning an array of users
        $this->users = $users;

    }

    public function clear(){
        $this->reset("users", "searchDispute");
    }


    
    

    public function render()
    {
        return view('livewire.search', [
            //'disputes' => $this->disputes,
            'users' => $this->users,
            'searchDispute' => $this->searchDispute,
        ]);
    }
}
