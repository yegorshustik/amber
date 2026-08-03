<?php

namespace App\Http\Resources\Api\Articles;

use App\Models\Articles\Rubric;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RubricResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Rubric $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
