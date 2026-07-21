<?php

namespace App\Http\Resources\Api\Inbox;

use App\Models\Inbox\Field;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Field $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
