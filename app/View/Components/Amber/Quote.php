<?php

namespace App\View\Components\Amber;

use App\Services\Api\MultilingualService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Quote extends Component
{
    public function __construct(
        public array $content = [],
    ) {}

    public function render(): View
    {
        return view('amber::components.quote');
    }
}
