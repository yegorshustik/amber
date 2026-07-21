<?php

namespace App\Casts\Api;

use App\Services\Api\ImageService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ImageCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = json_decode($value ?? '[]', true);

        return new ImageService($value ?? []);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            $key => json_encode($value),
        ];
    }
}
