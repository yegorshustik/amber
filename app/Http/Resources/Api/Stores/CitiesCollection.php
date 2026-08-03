<?php

namespace App\Http\Resources\Api\Stores;

use App\Models\Stores\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CitiesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (City $item) => $item->toResponse($request)),
        ];
    }
}
