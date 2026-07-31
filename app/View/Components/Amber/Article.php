<?php

namespace App\View\Components\Amber;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Article extends Component
{
    public function __construct(
        public \App\Models\Articles\Article $article,
    ) {}

    public function render(): View
    {
        return view('amber::components.article');
    }
}
