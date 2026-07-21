<?php

namespace App\Services\Api;

use App\Models\Banner;
use App\Models\Catalog\Brand;
use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Models\Review;
use App\Models\Stores\Store;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PageComposerService implements Responsable
{
    private array $content;

    public function __construct(?string $content = null)
    {
        $this->content = json_decode($content ?? '[]', true);
    }

    public function components(?array $components = null): Collection
    {
        return collect($components ?? $this->content ?? [])->map(function ($component) {
            switch ($component['name']) {
                case 'Text':
                    $component['content']['text'] = (new MultilingualService($component['content']['text']))->toString();
                    $component['content']['text'] = str_replace([
                        '<table',
                        '</table>',
                    ], [
                        '<div class="table-responsive"><table',
                        '</table></div>'
                    ], $component['content']['text']);

                    break;
            }

            return $component;
        });
    }

    public function toResponse($request)
    {
        return [
            'raw' => $this->content,
        ];
    }
}
