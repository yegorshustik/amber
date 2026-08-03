<?php

namespace App\Services\Api;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;

class MultilingualService implements Arrayable, Responsable
{
    private array $value;

    public function __construct(array|string|null $value = null)
    {
        $this->value = is_array($value) ? $value : json_decode($value ?? '[]', true);
    }

    public function locale(string $key): ?string
    {
        return $this->value[$key] ?? null;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function empty(): bool
    {
        return $this->toString() == '';
    }

    public function nl2br(): string
    {
        return nl2br($this->toString());
    }

    public function toString(): string
    {
        return \select_multilingual_field_value($this->value, '');
    }

    public function toResponse($request)
    {
        return array_map(function ($value) use ($request) {
            return $value instanceof Responsable ? $value->toResponse($request) : $value;
        }, $this->value);
    }

    public function toArray()
    {
        return \select_multilingual_field_value($this->value, '');
    }
}
