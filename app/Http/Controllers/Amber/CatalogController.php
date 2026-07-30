<?php

namespace App\Http\Controllers\Amber;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Services\Seo\Plugins\Breadcrumbs;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(): View
    {
        seo()->makeFromArray(config('system.catalog.seo'))->defaults([
            'title' => __('catalog.heading'),
            'h1' => __('catalog.heading'),
        ]);

        $breadcrumbs = new Breadcrumbs;

        $breadcrumbs->push([
            'url' => locale_url('catalog'),
            'title' => __('catalog.heading'),
        ]);

        seo()->extend($breadcrumbs);

        $catalog = Catalog::published()->get();

        $filterCountry = collect();
        $filterGender = collect();
        $filterBoarding = collect();
        $filterCampusStyle = collect();

        foreach ($catalog as $item) {
            $filterCountry->push($item->country->toString());
            $filterGender->push($item->gender->toString());
            $filterBoarding->push($item->boarding->toString());
            $filterCampusStyle->push($item->campus_style->toString());
        }

        $filterCountry = $filterCountry->filter(fn ($item) => $item != '')->unique()->sort()->values();
        $filterGender = $filterGender->filter(fn ($item) => $item != '')->unique()->sort()->values();
        $filterBoarding = $filterBoarding->filter(fn ($item) => $item != '')->unique()->sort()->values();
        $filterCampusStyle = $filterCampusStyle->filter(fn ($item) => $item != '')->unique()->sort()->values();

        $filters = collect([
            'country' => $filterCountry->map(fn ($item) => [
                'title' => $item,
                'slug' => md5($item),
            ]),
            'gender' => $filterGender->map(fn ($item) => [
                'title' => $item,
                'slug' => md5($item),
            ]),
            'boarding' => $filterBoarding->map(fn ($item) => [
                'title' => $item,
                'slug' => md5($item),
            ]),
            'campus_style' => $filterCampusStyle->map(fn ($item) => [
                'title' => $item,
                'slug' => md5($item),
            ]),
        ]);

        return view('amber::pages.catalog', compact('catalog', 'filters'));
    }

    public function show(string $slug): View
    {
        $item = Catalog::visible()->where('slug', $slug)->firstOrFail();

        seo()->makeFromModel($item)->defaults([
            'title' => $item->title,
            'h1' => $item->title,
        ]);
        $breadcrumbs = new Breadcrumbs;

        $breadcrumbs->push([
            'url' => locale_url('catalog'),
            'title' => __('catalog.directory'),
        ]);

        $breadcrumbs->push([
            'url' => $item->url,
            'title' => $item->title,
        ]);

        seo()->extend($breadcrumbs);

        return view('amber::pages.catalog-item', compact('item'));
    }
}
