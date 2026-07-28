<?php

namespace App\Http\Controllers\Amber;

use App\Http\Controllers\Controller;
use App\Models\Equipment\Category;
use App\Models\Equipment\Model;
use App\Models\Page;
use App\Services\Seo\Plugins\Breadcrumbs;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index()
    {
        $page = Page::site()->whereUrl('index')->published()->firstOrFail();

        seo()->makeFromArray(config('system.faq.seo'))->defaults([
            'title' => __('faq'),
            'h1' => __('faq'),
        ]);

        $breadcrumbs = new Breadcrumbs;
        $breadcrumbs->push([
            'url' => $page->url,
            'title' => $page->title,
        ]);
        $breadcrumbs->push([
            'url' => route('amber.faq'),
            'title' => __('faq'),
        ]);

        seo()->extend($breadcrumbs);

        return view('amber::pages.faq');
    }
}
