<?php

namespace App\Http\Resources\Api\Filemanager;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'file_name' => $this->file_name,
            'mime' => $this->mime,
            'size' => $this->size,
            'path' => $this->path,
            'extension' => pathinfo($this->file_name, PATHINFO_EXTENSION),
            'url' => Storage::url($this->path),
        ];
    }
}
