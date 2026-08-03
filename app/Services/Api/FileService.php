<?php

namespace App\Services\Api;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Storage;

class FileService implements Responsable
{
    public function __construct(private array $file = []) {}

    public function url(): ?string
    {
        $path = $this->file['src']['path'] ?? null;

        if ($path) {
            return Storage::url($path);
        }

        return null;
    }

    public function path(): ?string
    {
        return $this->file['src']['path'] ?? null;
    }

    public function exists(): bool
    {
        $path = $this->file['src']['path'] ?? null;

        if ($path) {
            return Storage::exists($path);
        }

        return false;
    }

    public function id(): ?int
    {
        return $this->file['src']['id'] ?? null;
    }

    public function title(): ?MultilingualService
    {
        return ($this->file['title'] ?? null) ? new MultilingualService($this->file['title']) : null;
    }

    public function filename(): ?string
    {
        return pathinfo($this->file['src']['path'] ?? null, PATHINFO_BASENAME) ?? null;
    }

    public function extension(): ?string
    {
        return pathinfo($this->file['src']['path'] ?? null, PATHINFO_EXTENSION) ?? null;
    }
    public function size(): ?int
    {
        if ($this->exists()) {
            return Storage::size($this->file['src']['path']);
        }

        return null;
    }

    public function __toString(): string
    {
        return $this->url() ?? '';
    }

    public function toArray()
    {
        return [
            'src' => [
                'id' => (int) $this->file['src']['id'] ?? null,
                'path' => $this->file['src']['path'] ?? null,
                'url' => $this->url(),
            ],
            'title' => $this->file['title'] ?? null,
        ];
    }

    public function toResponse($request)
    {
        return [
            'src' => [
                'id' => (int) ($this->file['src']['id'] ?? null),
                'path' => $this->file['src']['path'] ?? null,
                'url' => $this->url(),
            ],
            'alt' => $this->file['alt'] ?? null,
            'title' => $this->file['title'] ?? null,
        ];
    }
}
