<?php

namespace App\Http\Resources\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServicesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Service $item) => $item->toResponse($request)),
        ];
    }
}
