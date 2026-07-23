<?php

namespace App\Services\Api;

use App\Models\Review;
use Illuminate\Contracts\Support\Responsable;
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
                case 'Hero':
                    $component['content']['pre_heading'] = new MultilingualService($component['content']['pre_heading']);
                    $component['content']['heading']['text'] = new MultilingualService($component['content']['heading']['text']);
                    $component['content']['text'] = new MultilingualService($component['content']['text']);
                    $component['content']['image'] = new ImageService($component['content']['image']);
                    $component['content']['image_2'] = new ImageService($component['content']['image_2']);
                    break;

                case 'Section':
                    $component['content']['pre_heading'] = new MultilingualService($component['content']['pre_heading']);
                    $component['content']['heading']['text'] = new MultilingualService($component['content']['heading']['text'] ?? '[]');
                    $component['content']['text'] = new MultilingualService($component['content']['text']);
                    break;

                case 'Cta':
                    $component['content']['pre_heading'] = new MultilingualService($component['content']['pre_heading']);
                    $component['content']['heading']['text'] = new MultilingualService($component['content']['heading']['text']);
                    $component['content']['text'] = new MultilingualService($component['content']['text']);
                    $component['content']['button'] = new MultilingualService($component['content']['button']);
                    break;

                case 'Quote':
                    $component['content']['quote']['pre_heading'] = new MultilingualService($component['content']['quote']['pre_heading']);
                    $component['content']['quote']['text'] = new MultilingualService($component['content']['quote']['text']);
                    break;

                case 'Cards':
                    $component['content']['image'] = new ImageService($component['content']['image'] ?? []);
                    $component['content']['button'] = new MultilingualService($component['content']['button'] ?? null);
                    $component['content']['items'] = collect($component['content']['items'])->map(function ($item) {
                        $item['pre_heading'] = new MultilingualService($item['pre_heading']);
                        $item['heading'] = new MultilingualService($item['heading']);
                        $item['text'] = new MultilingualService($item['text']);
                        $item['image'] = new ImageService($item['image'] ?? []);

                        return $item;
                    });
                    break;

                case 'Text':
                    $component['content']['text'] = (new MultilingualService($component['content']['text']))->toString();
                    $component['content']['text'] = str_replace([
                        '<table',
                        '</table>',
                    ], [
                        '<div class="table-responsive"><table',
                        '</table></div>',
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
