<?php

namespace App\Livewire;

use Livewire\Component;

class AssignDisputes extends Component
{
    public $disputes;
    public $selectedDispute;
    public $justices = [];
    public $assignedJustices = [];
    public $meetingDate;

    protected $rules = [
        'assignedJustices' => 'required|array|min:1|max:3',
        'meetingDate' => 'required|date|after:now',
    ];

    public function mount(){
        
    }








    public function render()
    {
        return view('livewire.assign-disputes');
    }
}
