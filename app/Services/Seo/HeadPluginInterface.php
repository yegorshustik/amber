<?php

namespace App\Services\Seo;

interface HeadPluginInterface
{
    public function placement(): HeadPlacement;

    public function slug(): string;

    public function render(): string;
}
