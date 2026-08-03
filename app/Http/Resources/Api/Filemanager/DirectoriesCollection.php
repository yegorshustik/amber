<?php

namespace App\Http\Resources\Api\Filemanager;

use App\Models\Filemanager\Directory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DirectoriesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (Directory $directory) => new DirectoryResource($directory)),
        ];
    }
}
