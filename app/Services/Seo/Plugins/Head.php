<?php

namespace App\Services\Seo\Plugins;

use App\Services\Seo\HeadPlacement;
use App\Services\Seo\HeadPluginInterface;

class Head implements HeadPluginInterface
{
    public function placement(): HeadPlacement
    {
        return HeadPlacement::PREPEND;
    }

    public function slug(): string
    {
        return 'head';
    }

    public function render(): string
    {
        return '<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=5.0">';
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
