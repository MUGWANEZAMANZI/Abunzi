<?php

namespace App\Livewire;

use Livewire\Component;

class WelcomeController extends Component
{
    public function render()
    {
        sleep(5);
        return view('livewire.welcome-controller');
    }

    public function placeholder(){
        return view('livewire.welcome-placeholder');
    }
}
