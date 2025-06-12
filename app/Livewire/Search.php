<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\User;
use App\Models\Dispute;
use Illuminate\Support\Facades\Log;

#[Title('Search')]
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

        if($users->isNotEmpty()){
            foreach ($users as $user) {
                Log::info("User found: {$user->id} - {$user->name}");
            }
        } else {
            Log::info("No user found for search: {$value}");
            $this->disputes = [];
            return;
        }

        //returning an array of users
        $this->users = $users->toArray();

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
