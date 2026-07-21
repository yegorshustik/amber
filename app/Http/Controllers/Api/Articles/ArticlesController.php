<?php

namespace App\Http\Controllers\Api\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Articles\ArticleStoreRequest;
use App\Http\Resources\Api\Articles\ArticleResource;
use App\Http\Resources\Api\Articles\ArticlesCollection;
use App\Models\Articles\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function index(Request $request): ArticlesCollection
    {
        $query = Article::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->orderByDesc('articles.published_at');

        return new ArticlesCollection($query->paginate());
    }

    public function list(Request $request): ArticlesCollection
    {
        $limit = $request->input('limit', 8);

        return new ArticlesCollection(Article::query()->limit($limit)->orderByDesc('published_at')->get());
    }

    public function store(ArticleStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['site_id'] = site()->id;

        /** @var Article $article */
        $article = Article::create($validated);

        $article->rubrics()->sync($validated['rubrics']);
        $article->tags()->sync(collect($validated['tags'] ?? [])->map(fn ($tag) => $tag['id'])->toArray());

        return ArticleResource::make($article);
    }

    public function show(int $id): ArticleResource
    {
        return ArticleResource::make(Article::findOrFail($id));
    }

    public function update(ArticleStoreRequest $request, $id): ArticleResource
    {
        $validated = $request->validated();

        /** @var Article $article */
        $article = Article::findOrFail($id);
        $article->update($validated);

        $article->rubrics()->sync($validated['rubrics']);
        $article->tags()->sync(collect($validated['tags'] ?? [])->map(fn ($tag) => $tag['id'])->toArray());

        return ArticleResource::make($article);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        $article->delete();

        return response()->json(['data' => null]);
    }

    public function massDestroy(Request $request)
    {
        Article::findOrFail($request->input('ids'))->each(fn (Article $article) => $article->delete());

        return response()->json(['data' => null]);
    }
}
