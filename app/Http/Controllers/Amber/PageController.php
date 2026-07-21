<?php

namespace App\Http\Controllers\Amber;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\Seo\Plugins\Breadcrumbs;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $page = Page::site()->findUrl()->published()->firstOrFail();
        seo()->makeFromModel($page)->defaults([
            'title' => $page->title,
            'h1' => $page->title,
        ]);

        $breadcrumbs = new Breadcrumbs;

        foreach ($page->ancestors->where('parent_id', '!=', null) as $ancestor) {
            $breadcrumbs->push([
                'url' => $ancestor->url,
                'title' => $ancestor->title,
            ]);
        }

        if ($page->slug != 'index') {
            $breadcrumbs->push([
                'url' => $page->url,
                'title' => $page->title,
            ]);
        }


        seo()->extend($breadcrumbs);

        seo()->home = $page->slug == 'index';
        seo()->bodyClass = 'page-'.$page->slug;

        if (view()->exists('pages.'.$page->slug)) {
            return view('pages.'.$page->slug, compact('page'));
        }

        return view('amber::pages.page', compact('page'));
    }

    public function notFound(Response $response)
    {
        header('HTTP/1.0 404 Not Found', true, 404);

        seo()->makeFromArray([
            'title' => [
                app()->getLocale() => __('error-404.meta-title'),
            ],
        ]);

        echo view('amber::pages.error-404')->render();

        exit;
    }
}
