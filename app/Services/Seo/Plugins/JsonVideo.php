<?php

namespace App\Services\Seo\Plugins;

use App\Services\Seo\PluginInterface;

class JsonVideo implements PluginInterface
{
    public function slug(): string
    {
        return 'json-video';
    }

    public function render(): string
    {
        return '<script type="application/ld+json">'.json_encode(['test' => 'test2'], JSON_UNESCAPED_UNICODE).'</script>';
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
