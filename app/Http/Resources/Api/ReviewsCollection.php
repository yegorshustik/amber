<?php

namespace App\Http\Resources\Api;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Review $item) => $item->toResponse($request)),
        ];
    }
}
