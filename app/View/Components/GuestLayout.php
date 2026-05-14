<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public string $panelClass;
    public string $heroTitle;

    public function __construct(
        string $panelClass = 'bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700',
        string $heroTitle = 'Kelola Bisnis Lebih Mudah & Lebih Cepat'
    ) {
        $this->panelClass = $panelClass;
        $this->heroTitle  = $heroTitle;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
