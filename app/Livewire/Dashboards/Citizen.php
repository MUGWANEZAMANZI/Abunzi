<?php

namespace App\Livewire\Dashboards;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Umuturage')]
class Citizen extends Component
{


    public $openDisputeModal = false;
    public function OpenDisputeModal(){$this->openDisputeModal = true;}




    public function render()
    {
        return view('livewire.dashboards.citizen');
    }
}
