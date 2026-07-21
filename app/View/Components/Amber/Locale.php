<?php

namespace App\View\Components\Amber;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Locale extends Component
{
    public function render(): View
    {
        return view('amber::components.locale');
    }
}
