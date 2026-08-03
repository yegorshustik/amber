<?php

namespace App\View\Components\Amber;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Heading extends Component
{
    public function __construct(
        public string $level = 'h2',
        public ?string $style = null,
        public int $maxCharacters = 1000,
    ) {
        if ($this->level == 'none') {
            $this->level = 'div';
        }

        if ($this->style == 'none') {
            $this->style = null;
        }
    }

    public function render(): View|Closure|string
    {
        return view('amber::components.heading');
    }
}
