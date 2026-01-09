<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $noContainer;

    /**
     * Create a new component instance.
     */
    public function __construct($noContainer = false)
    {
        $this->noContainer = $noContainer;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
