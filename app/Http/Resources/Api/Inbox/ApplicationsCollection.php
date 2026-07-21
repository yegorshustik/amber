<?php

namespace App\Http\Resources\Api\Inbox;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApplicationsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($application) {
                $result = $application->toArray();

                $result['created_at'] = $application->created_at->format('Y-m-d H:i:s');

                return $result;
            }),
        ];
    }
}
