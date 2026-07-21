<?php

namespace App\Http\Resources\Api\Pages;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Page $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
