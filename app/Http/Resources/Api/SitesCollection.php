<?php

namespace App\Http\Resources\Api;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SitesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Site $item) => $item->toResponse($request)),
        ];
    }
}
