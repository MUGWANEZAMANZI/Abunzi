<?php

namespace App\Livewire;

use Livewire\Component;
use PhpParser\Node\Expr\Cast\String_;
use function PHPUnit\Framework\isTrue;

class AIMbaza extends Component
{
    public bool $isClicked = false;
    //public $name = false;
    public $prompt = '';

    public function showModal(){
        $this->isClicked = true;
          
    }

    public function close(){
        $this->isClicked = false;
        $this->prompt = '';
    }

    public function askAI(){
        $this->prompt = $this->prompt." Wihangane Nta makuru mbifiteho";
    }



    public function render()
    {
        return view('livewire.a-i-mbaza');
    }
}
