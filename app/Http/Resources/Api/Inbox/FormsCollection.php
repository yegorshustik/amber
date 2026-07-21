<?php

namespace App\Http\Resources\Api\Inbox;

use App\Models\Inbox\Form;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FormsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Form $item) => $item->toResponse($request)),
        ];
    }
}
