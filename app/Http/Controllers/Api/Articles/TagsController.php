<?php

namespace App\Http\Controllers\Api\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Articles\TagResource;
use App\Http\Resources\Api\Articles\TagsCollection;
use App\Models\Articles\Tag;
use App\Services\Localization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TagsController extends Controller
{
    public function __invoke(Request $request)
    {
        switch ($request->input('action')) {
            case 'search':
                return TagsCollection::make(Tag::query()->whereAny([
                    'title', 'slug',
                ], 'like', '%'.$request->input('q').'%')->get());

            case 'create':
                if (! $request->input('title')) {
                    throw ValidationException::withMessages([
                        'title' => [__('validation.required', ['attribute' => 'title'])],
                    ]);
                }

                $tag = Tag::create([
                    'site_id' => site()->id,
                    'title' => (new Localization)->fillLocalized($request->input('title')),
                    'slug' => Str::slug($request->input('title')),
                ]);

                return new TagResource($tag);

            case 'update':
                if (! $request->input('title')) {
                    throw ValidationException::withMessages([
                        'title' => [__('validation.required', ['attribute' => 'title'])],
                    ]);
                }

                $tag = Tag::findOrFail($request->input('id'));

                $tag->update([
                    'title' => $request->input('title'),
                    'slug' => Str::slug($request->input('slug', $request->input('title')[(new Localization)->default()['locale']])),
                ]);

                return new TagResource($tag);

            case 'destroy':
                Tag::findOrFail($request->input('id'))->delete();

                return response()->json(['data' => null]);

            default:
                abort(404);
        }
    }
}
