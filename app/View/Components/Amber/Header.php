<?php

namespace App\View\Components\Amber;

use App\Models\Catalog\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Header extends Component
{
    public function render(): View
    {
        return view('amber::components.header');
    }
}
