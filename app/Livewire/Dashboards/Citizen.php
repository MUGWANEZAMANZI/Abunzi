<?php

namespace App\Livewire\Dashboards;


use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Log;


#[Title('Umuturage')]
class Citizen extends Component
{
    protected $listeners = [
        'closeUpdated',
    ];


    public bool $open = false;
    public function openModal(){
        $this->open = true;
    }

    public function closeUpdated($isClosed){
        $this->open = $isClosed;
        $this->open = false;
        Log::info("Closed");

    }




    public function render()
    {
        return view('livewire.dashboards.citizen');
    }
}
