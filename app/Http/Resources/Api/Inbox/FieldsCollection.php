<?php

namespace App\Http\Resources\Api\Inbox;

use App\Models\Inbox\Field;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FieldsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Field $item) => $item->toResponse($request)),
        ];
    }
}
