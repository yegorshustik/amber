<?php

namespace App\Http\Resources\Api\Articles;

use App\Models\Articles\Rubric;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RubricsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Rubric $item) => $item->toResponse($request)),
        ];
    }
}
