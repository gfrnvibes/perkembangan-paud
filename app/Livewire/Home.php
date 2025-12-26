<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("RA Nurul Amin")]
#[Layout('components.layouts.app')]

class Home extends Component
{
    public function render()
    {
        return view('livewire.home');
    }
}
