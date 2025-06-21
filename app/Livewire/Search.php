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
    public $users = '';

     #[Validate('date')]
    public $searchDateFrom = '';

    public $searchDateTo = '';
   





    public function updatedSearchDispute($value)
    {
        $this->reset("users");

        $this->validate();
        



        $users = User::with(['disputes' => function ($query) {
        $query->when($this->searchDateFrom, fn($q) => $q->where('created_at', '>=', $this->searchDateFrom))
              ->when($this->searchDateTo, fn($q) => $q->where('created_at', '<=', $this->searchDateTo));
            }])
            ->where("identification", "like", "{$value}%")
            ->whereNotIn("role", ["justice", "chief"])
            ->get();


        
         
        
            
        if($users->isNotEmpty()){
            foreach ($users as $user) {
                Log::info("User found: {$user->id} - {$user->name}");
                Log::info("Disputes found for user {$user->id}: " . ($user->disputes ? $user->disputes->count() : 0));
            }
        } else {
            Log::info("No user found for search: {$value}");
            $this->disputes = [];
            return;
        }

        //returning an object with disputes
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
