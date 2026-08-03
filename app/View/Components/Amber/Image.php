<?php

namespace App\View\Components\Amber;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Image extends Component
{
    public function __construct(
        public array $content = [],
    ) {}

    public function render(): View
    {
        return view('amber::components.image');
    }
}
