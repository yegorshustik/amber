<?php

namespace App\Services\Api;

use Illuminate\Contracts\Support\Responsable;

class SeoService implements Responsable
{
    public function __construct(private array $seo = []) {}

    public function get(string $param)
    {
        if ($param == 'og') {
            return isset($this->seo[$param]) ? new ImageService($this->seo[$param] ?? []) : null;
        }

        return new MultilingualService($this->seo[$param] ?? null);
    }

    public function toResponse($request)
    {
        return [
            'og' => $this->seo['og'] ?? null,
            'title' => $this->seo['title'] ?? null,
            'keywords' => $this->seo['keywords'] ?? null,
            'description' => $this->seo['description'] ?? null,
            'h1' => $this->seo['h1'] ?? null,
        ];
    }
}
