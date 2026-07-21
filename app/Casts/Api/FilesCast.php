<?php

namespace App\Casts\Api;

use App\Services\Api\FilesService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class FilesCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = json_decode($value ?? '[]', true);

        return new FilesService($value ?? []);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            $key => json_encode($value),
        ];
    }
}
