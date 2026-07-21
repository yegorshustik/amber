<?php

namespace App\Services\Seo;

interface PluginInterface
{
    public function slug(): string;

    public function render(): string;
}
