<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Loader extends Component
{
    /**
     * Create a new component instance.
     */
    public $wireTarget;

    public function __construct($wireTarget)
    {
        $this->wireTarget = $wireTarget;
    }

    public function render()
    {
        return view('components.loader');
    }
}
