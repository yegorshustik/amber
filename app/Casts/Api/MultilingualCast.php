<?php

namespace App\Casts\Api;

use App\Services\Api\MultilingualService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MultilingualCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes): MultilingualService
    {
        return new MultilingualService($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
