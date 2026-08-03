<?php

namespace App\Services;

class Context
{
    protected static array $context = [];

    public static function provide($key, array $context): void
    {
        self::$context[$key] = $context;
    }

    public static function inject($key, array $defaults = []): array
    {
        return self::$context[$key] ?? $defaults ?? [];
    }
}
