<?php

namespace App\View\Components\Amber;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Toc extends Component
{
    public function __construct(
        public bool $hideToc = false,
    ) {}

    public function render(): View
    {
        return view('amber::components.toc');
    }
}
