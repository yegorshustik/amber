<?php

namespace App\Http\Resources\Api\Stores;

use App\Models\Stores\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StoresCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Store $item) => $item->toResponse($request)),
        ];
    }
}
