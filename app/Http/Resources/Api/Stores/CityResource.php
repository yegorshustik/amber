<?php

namespace App\Http\Resources\Api\Stores;

use App\Models\Stores\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var City $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
