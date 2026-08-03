<?php

namespace App\Http\Resources\Api\Filemanager;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DirectoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'children' => [],
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'position' => $this->position,
            'title' => $this->title,
        ];
    }
}
