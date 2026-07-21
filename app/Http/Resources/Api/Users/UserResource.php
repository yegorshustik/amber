<?php

namespace App\Http\Resources\Api\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /**
         * @var User $resource
         */
        $resource = $this->resource;

        return $resource->toResponse($request);
    }
}
