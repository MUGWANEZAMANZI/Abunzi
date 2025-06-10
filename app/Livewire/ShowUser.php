<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Dispute;


#[Title("User Details")]
class ShowUser extends Component
{
    public User $user;
    public $disputes = [];
    public $lastActivity = null;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->disputes = Dispute::where('citizen_id', $user->id)->get();
        $lastActivity = \DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->first();
        $this->lastActivity = $lastActivity ? \Carbon\Carbon::createFromTimestamp($lastActivity->last_activity)->diffForHumans() : 'No activity recorded';
    }


    public function render()
    {
        return view('livewire.show-user', ['user' => $this->user, 'disputes' => $this->disputes, 'lastActivity' => $this->lastActivity]);
    }
}
