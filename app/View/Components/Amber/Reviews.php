<?php

namespace App\View\Components\Amber;

use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Reviews extends Component
{
    public function reviews(): Collection
    {
        return Review::items(25);
    }

    public function render(): View
    {
        return view('amber::components.reviews');
    }
}
