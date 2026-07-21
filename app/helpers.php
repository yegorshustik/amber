<?php

use App\Models\Site;
use App\Services\Cache;
use App\Services\Localization;
use App\Services\Seo;

if (! function_exists('fillArray')) {
    function fillArray(array $template, array $params): array
    {
        $result = [];

        $lastValue = 0;

        foreach ($template as $item) {
            if (isset($params[$item])) {
                $lastValue = $params[$item];
            }

            $result[$item] = $lastValue;
        }

        return $result;
    }
}

if (! function_exists('seo')) {
    function seo(): Seo
    {
        return Seo::getInstance();
    }
}

if (! function_exists('site')) {
    function site(): ?Site
    {
        return Cache::remember('selected-site', function () {

            $id = request()->header('x-selected-site');

            if ($id) {
                return Site::find($id) ?? Site::first();
            }

            $site = Site::published()->findByDomain(request()->host())->first();

            return $site ?? Site::first();
        });
    }
}

if (! function_exists('locale_prefix')) {
    function locale_prefix(): ?string
    {
        $locale = request()->segment(1);

        if ($locale == 'cms') {
            return '';
        }

        $locales = (new Localization)->available();
        $default = (new Localization)->default();

        if ($default['locale'] == $locale) {
            App::setLocale($default['locale']);

            return null;
        }

        if ($selected = $locales->where('locale', $locale)->first()) {
            App::setLocale($selected['locale']);

            return $selected['locale'];
        }

        App::setLocale($default['locale']);

        return null;
    }
}

if (! function_exists('locale_url')) {
    function locale_url(?string $url, bool $absolute = false, ?string $locale = null): string
    {
        if (str_starts_with($url, '#')) {
            return $url;
        }

        if (! $locale) {
            $current = (new Localization)->current();
        } else {
            $current = (new Localization)->available()->where('locale', $locale)->first();
        }

        if (! $url) {
            return ($absolute ? request()->getSchemeAndHttpHost() : '').($current['default'] ? '/' : ('/'.$current['locale']));
        }

        $url = trim($url, '/') ?: '/';

        $url = (new Localization)->clearUrl($url);

        return ($absolute ? request()->getSchemeAndHttpHost() : '').($current['default'] ? ('/'.trim($url, '/')) : ('/'.trim($current['locale'].'/'.$url, '/')));
    }
}

if (! function_exists('url_starts_with')) {
    function url_starts_with(?string $segment = null): bool
    {
        $url ??= request()->path();
        $url = (new Localization)->clearUrl($url);

        return str_starts_with(trim($url, '/'), trim($segment, '/'));
    }
}

if (! function_exists('toggle_language')) {
    function toggle_language(string $locale): string
    {
        $url ??= request()->path();
        $url = (new Localization)->clearUrl($url);

        $lang = (new Localization)->available()->where('locale', $locale)->first();

        if (! $lang || $lang['default']) {
            return str_replace('//', '/', '/'.trim($url));
        }

        return str_replace('//', '/', '/'.trim($lang['locale'].'/'.$url, '/'));
    }
}

if (! function_exists('select_multilingual_field_value')) {
    function select_multilingual_field_value($data, $default = null)
    {
        if (! is_array($data)) {
            $data = json_decode($data, true);
        }

        if (str_starts_with(request()->path(), 'cms')) {
            $language = (new Localization)->default()['locale'];
        } else {
            $language = (new Localization)->current()['locale'];
        }

        if (! isset($data[$language]) || $data[$language] == '') {
            return $data[(new Localization)->default()['locale']] ?? $default ?? null;
        }

        return $data[(new Localization)->current()['locale']] ?? $default ?? null;
    }
}

if (! function_exists('is_multilingual_field_empty')) {
    function is_multilingual_field_empty($data): bool
    {
        if (! is_array($data)) {
            $data = json_decode($data, true);
        }

        foreach ((new Localization)->available() as $locale) {
            if (isset($data[$locale['locale']]) && $data[$locale['locale']] != '') {
                return false;
            }
        }

        return true;
    }
}

if (! function_exists('prepareKeywords')) {
    function prepareKeywords($keywords): string
    {
        $keywords = explode(' ', $keywords);
        $keywords = array_filter($keywords);
        $keywords = array_map(fn ($keyword) => Str::lower(str_replace([
            "\n",
            "\t",
            '(',
            ')',
            '"',
            "'",
            '—',
            '-',
            ',',
            '?',
            '.',
        ], '', $keyword)), $keywords);
        $keywords = array_map('trim', $keywords);
        $keywords = array_unique($keywords);
        $keywords = array_filter($keywords, function ($keyword) {
            return mb_strlen($keyword) >= 2;
        });
        $keywords = array_unique($keywords);
        $keywords = array_values($keywords);

        return implode(' ', $keywords);
    }
}

if (! function_exists('clearPhone')) {
    function clearPhone(?string $phone): ?string
    {
        return str_replace([
            ' ',
            '(',
            ')',
            '-',
        ], '', $phone);
    }
}

if (! function_exists('isExternalUrl')) {
    function isExternalUrl(?string $url = null): bool
    {
        if (! $url) {
            return false;
        }

        return str_starts_with($url, 'http');
    }
}

if (! function_exists('replaceContacts')) {
    function replaceContacts(?string $content = null): ?string
    {
        if (! $content) {
            return null;
        }

        $content = preg_replace(
            '/([\w.-]+@[\w.-]+\.[a-zA-Z]{2,6})/',
            '<a href="mailto:$1">$1</a>',
            $content
        );

        $phonePattern = '/\+38\s*\(?\d{3}\)?[\s-]*\d{3}[\s-]*\d{2}[\s-]*\d{2}/';

        $content = preg_replace_callback($phonePattern, function ($matches) {
            $cleanPhone = preg_replace('/[^\d+]/', '', $matches[0]);

            return '<a href="tel:'.$cleanPhone.'">'.$matches[0].'</a>';
        }, $content);

        return $content;
    }
}
