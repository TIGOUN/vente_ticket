<?php

namespace App\Livewire\Dashbords;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

// #[Layout('layouts.app')]
class StarterPage extends Component
{

    public function mount() {}

    public function render()
    {
        return view('livewire.dashbords.starter-page');
    }
}