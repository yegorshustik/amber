<?php

namespace App\Http\Resources\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Service $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
