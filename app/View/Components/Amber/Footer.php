<?php

namespace App\View\Components\Amber;

use App\Services\Api\MultilingualService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Footer extends Component
{
    public function render(): View
    {
        return view('amber::components.footer');
    }
}
