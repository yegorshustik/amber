<?php

namespace App\View\Components\Amber;

use App\Services\Api\PageComposerService;
use Illuminate\View\Component;
use Illuminate\View\View;

class PageComposer extends Component
{
    public function __construct(
        public PageComposerService $content,
        public ?array $children = null,
        public int $level = 0
    ) {}

    public function render(): View
    {
        return view('amber::components.page-composer');
    }
}
