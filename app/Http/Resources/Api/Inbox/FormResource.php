<?php

namespace App\Http\Resources\Api\Inbox;

use App\Models\Inbox\Form;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Form $resource */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
