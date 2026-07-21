<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class Localization
{
    public function available(): Collection
    {
        return collect(config('localization.list'))->where('visible', true)->values();
    }

    public function cmsDefault(): array
    {
        return $this->available()->where('cms_default', true)->first();
    }

    public function default(): array
    {
        return $this->available()->where('default', true)->first();
    }

    public function current(): array
    {
        $current = $this->available()->where('locale', App::getLocale())->first();

        if (! $current) {
            return $this->default();
        }

        return $current;
    }

    public function clearUrl(?string $url = null): string
    {
        if (! $url) {
            $url = request()->path();
        }

        $segments = explode('/', $url);

        if (isset($segments[0]) && $this->available()->where('locale', $segments[0])->first()) {
            unset($segments[0]);
            $segments = array_values($segments);
        }

        return implode('/', $segments);
    }

    public function fillLocalized(string $value): array
    {
        $locales = [];

        foreach ($this->available() as $locale) {
            $locales[$locale['locale']] = $value;
        }

        return $locales;
    }
}
