<?php

namespace App\View\Components\Amber;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Section extends Component
{
    public function __construct(
        public string $color = 'default',
    ) {}

    public function render(): View
    {
        return view('amber::components.section');
    }
}
