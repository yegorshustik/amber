<?php

namespace App\Http\Controllers\Amber;

use App\Models\Articles\Article;
use App\Models\Articles\Rubric;
use App\Models\Page;
use App\Services\Seo\Plugins\Breadcrumbs;

class ArticlesController
{
    public function index(Rubric $rubric)
    {
        $page = Page::site()->whereUrl('index')->published()->firstOrFail();

        seo()->makeFromModel($rubric)->defaults([
            'title' => $rubric->title,
            'h1' => $rubric->title,
        ]);

        $breadcrumbs = new Breadcrumbs;
        $breadcrumbs->push([
            'url' => $page->url,
            'title' => $page->title,
        ]);
        $breadcrumbs->push([
            'url' => $rubric->url,
            'title' => $rubric->title,
        ]);

        seo()->extend($breadcrumbs);

        $articles = $rubric->articles()->paginate(100);

        return view('amber::pages.rubric', compact('rubric', 'articles'));
    }

    public function show(string $slug)
    {
        $page = Page::site()->whereUrl('index')->published()->firstOrFail();
        $article = Article::with(['rubrics', 'tags'])->published()->whereSlug($slug)->firstOrFail();

        $blog = $article->rubrics->where('slug', 'blog')->first();
        $rubric = $article->rubrics->where('slug', '!=', 'blog')->first();
        $related = $rubric->articles()->published()->where('id', '!=', $article->id)->take(4)->get();

        seo()->makeFromModel($article)->defaults([
            'title' => $article->title,
            'h1' => $article->title,
        ]);

        $breadcrumbs = new Breadcrumbs;

        if ($blog) {
            $breadcrumbs->push([
                'url' => $blog->url,
                'title' => $blog->title,
            ]);
        }
        if ($rubric) {
            $breadcrumbs->push([
                'url' => $rubric->url,
                'title' => $rubric->title,
            ]);
        }

        seo()->extend($breadcrumbs);

        return view('amber::pages.article', compact('article', 'related'));
    }
}
