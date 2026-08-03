<?php

namespace App\View\Components\Amber;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TextBlock extends Component
{
    public function __construct(
        public array $content = [],
    ) {}

    public function isAdditionalFilled(): bool
    {
        return !($this->content['additional']['pre_heading']?->empty() || $this->content['additional']['heading']['text']?->empty() || $this->content['additional']['text']?->empty());
    }

    public function render(): View
    {
        return view('amber::components.text-block');
    }
}
