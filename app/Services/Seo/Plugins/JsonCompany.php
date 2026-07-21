<?php

namespace App\Services\Seo\Plugins;

use App\Services\Seo\HeadPlacement;
use App\Services\Seo\HeadPluginInterface;

class JsonCompany implements HeadPluginInterface
{
    public function placement(): HeadPlacement
    {
        return HeadPlacement::APPEND;
    }

    public function slug(): string
    {
        return 'json-company';
    }

    public function render(): string
    {
        return '<script type="application/ld+json">'.json_encode([], JSON_UNESCAPED_UNICODE).'</script>';
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
