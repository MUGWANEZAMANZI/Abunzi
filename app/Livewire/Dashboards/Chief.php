<?php

namespace App\Livewire\Dashboards;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Umwunzi mukuru')]
class Chief extends Component
{
    public function render()
    {
        return view('livewire.dashboards.chief');
    }
}
