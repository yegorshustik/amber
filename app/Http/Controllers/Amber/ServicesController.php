<?php

namespace App\Http\Controllers\Amber;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Service;
use App\Services\Seo\Plugins\Breadcrumbs;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function show(string $slug): View
    {
        $service = Service::published()->whereSlug($slug)->firstOrFail();

//        /$page = Page::site()->whereUrl('index')->published()->firstOrFail();

        seo()->makeFromModel($service)->defaults([
            'title' => $service->title,
            'h1' => $service->title,
        ]);

        $breadcrumbs = new Breadcrumbs;

        $breadcrumbs->push([
            'url' => locale_url('services'),
            'title' => __('services'),
        ]);

        $breadcrumbs->push([
            'url' => $service->url,
            'title' => $service->title,
        ]);

        seo()->extend($breadcrumbs);

        return view('amber::pages.service', compact('service'));
    }
}
