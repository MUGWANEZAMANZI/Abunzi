<?php

namespace App\Livewire\Dashboards;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Justice Dashboard')]
class Justice extends Component
{
    public function render()
    {
        return view('livewire.dashboards.justice');
    }
}
