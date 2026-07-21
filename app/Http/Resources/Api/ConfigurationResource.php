<?php

namespace App\Http\Resources\Api;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Configuration */
class ConfigurationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Configuration $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
