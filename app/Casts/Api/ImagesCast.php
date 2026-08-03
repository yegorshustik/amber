<?php

namespace App\Casts\Api;

use App\Services\Api\ImagesService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ImagesCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = json_decode($value ?? '[]', true);

        return new ImagesService($value ?? []);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            $key => json_encode($value),
        ];
    }
}
