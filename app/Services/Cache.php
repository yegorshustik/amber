<?php

namespace App\Services;

use Closure;

class Cache
{
    protected static array $cache = [];

    public static function remember($key, Closure $callback, bool $force = false): mixed
    {
        if (! isset(self::$cache[$key]) || $force) {
            self::$cache[$key] = $callback();
        }

        return self::$cache[$key];
    }

    public static function get($key): mixed
    {
        return self::$cache[$key] || null;
    }
}
