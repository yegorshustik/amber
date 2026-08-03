<?php

namespace App\Http\Resources\Api\Stores;

use App\Models\Stores\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Store $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
