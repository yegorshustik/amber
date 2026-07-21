<?php

namespace App\Http\Resources\Api\Articles;

use App\Models\Articles\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticlesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Article $item) => $item->toResponse($request)),
        ];
    }
}
