<?php

namespace App\View\Components\Amber;

use App\Services\Api\ImageService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Cards extends Component
{
    public function __construct(
        public string $type = 'default',
        public ?ImageService $image = null,
        public Collection $cards = new Collection,
    ) {}

    public function render(): View
    {
        return view('amber::components.cards');
    }
}
