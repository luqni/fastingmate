<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $noContainer;
    public $hideHeader;
    public $hideBottomNav;
    public $tadabbur;

    /**
     * Create a new component instance.
     */
    public function __construct($noContainer = false, $hideHeader = false, $hideBottomNav = false, $tadabbur = null)
    {
        $this->noContainer = $noContainer;
        $this->hideHeader = $hideHeader;
        $this->hideBottomNav = $hideBottomNav;
        $this->tadabbur = $tadabbur;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
