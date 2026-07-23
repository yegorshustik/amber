<?php

namespace App\Services\Api;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Storage;

class ImageService implements Responsable
{
    public function __construct(private array $image = []) {}

    public function url(): ?string
    {
        $path = $this->image['src']['path'] ?? null;

        if ($path) {
            return Storage::url($path);
        }

        return null;
    }

    public function body(): ?string
    {
        $path = $this->image['src']['path'] ?? null;

        if ($path) {
            return Storage::get($path);
        }

        return null;
    }

    public function exists(): bool
    {
        $path = $this->image['src']['path'] ?? null;

        if ($path) {
            return Storage::exists($path);
        }

        return false;
    }

    public function alt(): ?MultilingualService
    {
        if (isset($this->image['alt']) && is_array($this->image['alt'])) {
            return new MultilingualService($this->image['alt']);
        }

        return $this->image['alt'] ?? null;
    }

    public function title(): ?MultilingualService
    {
        if (isset($this->image['title']) && is_array($this->image['title'])) {
            return new MultilingualService($this->image['title']);
        }

        return $this->image['title'] ?? null;
    }

    public function __toString(): string
    {
        return $this->url() ?? '';
    }

    public function toArray()
    {
        return [
            'src' => [
                'id' => (int) $this->image['src']['id'] ?? null,
                'path' => $this->image['src']['path'] ?? null,
                'url' => $this->url(),
            ],
            'alt' => $this->image['alt'] ?? null,
            'title' => $this->image['title'] ?? null,
        ];
    }

    public function toResponse($request)
    {
        return [
            'src' => [
                'id' => (int) ($this->image['src']['id'] ?? null),
                'path' => $this->image['src']['path'] ?? null,
                'url' => $this->url(),
            ],
            'alt' => isset($this->image['alt']) ? ($this->image['alt'] instanceof MultilingualService ? $this->image['alt']->toResponse($request) : $this->image['alt']) : null,
            'title' => isset($this->image['title']) ? ($this->image['title'] instanceof MultilingualService ? $this->image['title']->toResponse($request) : $this->image['title']) : null,
        ];
    }
}
