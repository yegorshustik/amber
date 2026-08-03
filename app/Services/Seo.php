<?php

namespace App\Services;

use App\Services\Api\SeoService;
use App\Services\Seo\Access;
use App\Services\Seo\HeadPlacement;
use App\Services\Seo\HeadPluginInterface;
use ArrayAccess;
use Illuminate\Database\Eloquent\Model;

class Seo implements ArrayAccess
{
    use Access;

    private static Seo $instance;

    protected array $defaults = [];

    protected array $templates = [
        'title' => '<title>:param</title>',
        'keywords' => '<meta name="keywords" content=":param" />',
        'description' => '<meta name="description" content=":param" />',
        'og:site_name' => '<meta name="og:site_name" content=":param" />',
        'og:url' => '<meta name="og:url" content=":param" />',
        'og:title' => '<meta name="og:title" content=":param" />',
        'og:description' => '<meta name="og:description" content=":param" />',
        'og:image' => '<meta name="og:image" content=":param" />',
    ];

    public static function getInstance(bool $force = false): self
    {
        if ($force) {
            return new self;
        }

        if (empty(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function makeFromModel(Model $model, string $attribute = 'seo'): self
    {
        $url = '/'.trim((new Localization)->clearUrl(), '/');

        $seo_block = null;

        $raw_seo = $model->getAttribute($attribute);

        $default_seo = new SeoService([]);

        $findBest = function ($key) use ($raw_seo, $default_seo): array {
            $data = [];

            foreach ((new Localization)->available() as $lang) {
                if (($seo_param = $default_seo?->get($key)) != null && $seo_param->locale($lang['locale'])) {
                    $data[$lang['locale']] = $seo_param->locale($lang['locale']);
                } elseif (($seo_param = $raw_seo->get($key)) != null && $seo_param->locale($lang['locale'])) {
                    $data[$lang['locale']] = $seo_param->locale($lang['locale']);
                } else {
                    $data[$lang['locale']] = null;
                }
            }

            return $data;
        };

        $og = $default_seo?->get('og') ?? $raw_seo?->get('og') ?? config('system.seo.default-og');

        $this->data = [
            'title' => $findBest('title'),
            'h1' => $findBest('h1'),
            'keywords' => $findBest('keywords'),
            'description' => $findBest('description'),
            'content' => $seo_block?->content ?? null,
            'og:title' => $findBest('title'),
            'og:description' => $findBest('description'),
            'og:image' => $og,
        ];

        if ($model->locales) {
            seo()->availableLocales = $model->locales->pluck('locale')->toArray();
        }

        return $this;
    }

    public function makeFromArray(array $data): self
    {
        $url = '/'.trim((new Localization)->clearUrl(), '/');

        $seo_block = null;

        $raw_seo = new SeoService($data);
        $default_seo = new SeoService([]);

        $findBest = function ($key) use ($raw_seo, $default_seo): array {
            $data = [];

            foreach ((new Localization)->available() as $lang) {
                if (($seo_param = $default_seo?->get($key)) != null && $seo_param->locale($lang['locale'])) {
                    $data[$lang['locale']] = $seo_param->locale($lang['locale']);
                } elseif (($seo_param = $raw_seo->get($key)) != null && $seo_param->locale($lang['locale'])) {
                    $data[$lang['locale']] = $seo_param->locale($lang['locale']);
                } else {
                    $data[$lang['locale']] = null;
                }
            }

            return $data;
        };

        $og = $default_seo?->get('og') ?? $raw_seo?->get('og') ?? config('system.seo.default-og');

        $this->data = [
            'title' => $findBest('title'),
            'h1' => $findBest('h1'),
            'keywords' => $findBest('keywords'),
            'description' => $findBest('description'),
            'content' => $seo_block?->content ?? null,
            'og:title' => $findBest('title'),
            'og:description' => $findBest('description'),
            'og:image' => $og,
        ];

        return $this;
    }

    public function defaults(array $defaults = []): self
    {
        $this->defaults = array_merge($this->defaults, $defaults);

        return $this;
    }

    public function render(): string
    {
        $html = '';

        foreach ($this->extensions as $extension => $plugin) {
            if ($plugin instanceof HeadPluginInterface && $plugin->placement() == HeadPlacement::PREPEND) {
                $html .= $plugin->render();
            }
        }
        foreach ($this->templates as $param => $template) {
            if ($param = $this->get($param)) {
                $html .= str_replace([
                    ':param',
                ], [
                    $param,
                ], $template);
            }
        }

        foreach ($this->extensions as $extension => $plugin) {
            if ($plugin instanceof HeadPluginInterface && $plugin->placement() == HeadPlacement::APPEND) {
                $html .= $plugin->render();
            }
        }

        return $html;
    }
}
