<?php

namespace App\Services\Seo\Plugins;

use App\Services\Seo\PluginInterface;
use Illuminate\Support\Collection;

class Breadcrumbs implements PluginInterface
{
    private Collection $breadcrumbs;

    public function __construct(?iterable $source = null, ?callable $handle = null)
    {
        $this->breadcrumbs = collect($source);

        if ($source && $handle) {
            $this->breadcrumbs = $this->breadcrumbs->transform($handle);
        }
    }

    public function items(): Collection
    {
        return $this->breadcrumbs;
    }

    public function count(): int
    {
        return $this->breadcrumbs->count();
    }

    public function push(array $item): self
    {
        $this->breadcrumbs->push($item);

        return $this;
    }

    public function slug(): string
    {
        return 'breadcrumbs';
    }

    public function render(): string
    {
        $parts = [];

        foreach ($this->breadcrumbs as $i => $breadcrumb) {
            if ($i + 1 == count($this->breadcrumbs)) {
                $parts[] = '<span>'.$breadcrumb['title'].'</span>';
            } else {
                $parts[] = '<a href="'.$breadcrumb['url'].'">'.$breadcrumb['title'].'</a> / ';
            }
        }

        if (count($parts) > 1) {
            return implode(' ', $parts);
        }

        return '';
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
