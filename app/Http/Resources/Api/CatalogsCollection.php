<?php

namespace App\Http\Resources\Api;

use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CatalogsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Catalog $item) => $item->toResponse($request)),
        ];
    }
}
