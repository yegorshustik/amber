<?php

namespace App\Http\Resources\Api\Articles;

use App\Models\Articles\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Article $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
