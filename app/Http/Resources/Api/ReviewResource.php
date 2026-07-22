<?php

namespace App\Http\Resources\Api;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Review $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
