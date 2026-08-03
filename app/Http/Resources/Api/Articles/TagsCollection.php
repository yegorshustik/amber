<?php

namespace App\Http\Resources\Api\Articles;

use App\Models\Articles\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Tag $item) => $item->toResponse($request)),
        ];
    }
}
