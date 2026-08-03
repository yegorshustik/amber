<?php

namespace App\Http\Resources\Api;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Site $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
