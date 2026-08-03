<?php

namespace App\Http\Resources\Api;

use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Catalog $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
