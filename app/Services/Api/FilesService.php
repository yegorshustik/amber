<?php

namespace App\Services\Api;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class FilesService extends Collection implements Responsable
{
    public function __construct($items = [])
    {
        $items = array_values($items ?? []);

        for ($i = 0; $i < count($items); $i++) {
            if (is_array($items[$i])) {
                $items[$i] = new FileService($items[$i]);
            }
        }

        parent::__construct($items);
    }

    public function toArray()
    {
        $image = [];
        foreach ($this->items as $item) {
            $image[] = $item->toArray();
        }

        return $image;
    }

    public function toResponse($request)
    {
        $image = [];
        foreach ($this->items as $item) {
            $image[] = $item->toResponse($request);
        }

        return $image;
    }
}
