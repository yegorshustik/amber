<?php

namespace App\View\Components\Amber;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Hero extends Component
{
    public function __construct(
        public array $content
    ) {}

    public function render(): View
    {
        return view('amber::components.hero');
    }
}
