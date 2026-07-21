<?php

namespace App\Casts\Api;

use App\Services\Api\PageComposerService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PageComposerCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return new PageComposerService($value ?? null);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            $key => $value,
        ];
    }
}
