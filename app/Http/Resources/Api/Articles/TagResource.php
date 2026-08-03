<?php

namespace App\Http\Resources\Api\Articles;

use App\Models\Articles\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Tag $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
