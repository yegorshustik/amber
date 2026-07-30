<?php

namespace App\Services\Api;

use App\Models\Inbox\Form;
use App\Models\Review;
use App\Models\Service;
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

                case 'Headline':
                    $component['content']['pre_heading'] = new MultilingualService($component['content']['pre_heading']);
                    $component['content']['heading']['text'] = new MultilingualService($component['content']['heading']['text']);
                    $component['content']['text'] = new MultilingualService($component['content']['text']);
                    $component['content']['button_1'] = new MultilingualService($component['content']['button_1']);
                    $component['content']['button_2'] = new MultilingualService($component['content']['button_2']);
                    break;

                case 'TextBlock':
                    $component['content']['pre_heading'] = new MultilingualService($component['content']['pre_heading']);
                    $component['content']['heading']['text'] = new MultilingualService($component['content']['heading']['text']);
                    $component['content']['text'] = new MultilingualService($component['content']['text']);
                    $component['content']['button'] = new MultilingualService($component['content']['button'] ?? null);
                    $component['content']['reverse'] = ($component['content']['reverse'] ?? '0') == '1';

                    $component['content']['additional']['pre_heading'] = new MultilingualService($component['content']['additional']['pre_heading'] ?? null);
                    $component['content']['additional']['heading']['text'] = new MultilingualService($component['content']['additional']['heading']['text'] ?? null);
                    $component['content']['additional']['text'] = new MultilingualService($component['content']['additional']['text'] ?? null);
                    $component['content']['additional']['button'] = new MultilingualService($component['content']['additional']['button'] ?? null);

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
                    $component['content']['quote']['pre_heading'] = new MultilingualService($component['content']['quote']['pre_heading'] ?? null);
                    $component['content']['quote']['text'] = new MultilingualService($component['content']['quote']['text'] ?? null);
                    $component['content']['quote']['name'] = new MultilingualService($component['content']['quote']['name'] ?? null);
                    $component['content']['quote']['job'] = new MultilingualService($component['content']['quote']['job'] ?? null);
                    $component['content']['quote']['image'] = new ImageService($component['content']['quote']['image'] ?? []);
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

                case 'Image':
                    $image = select_multilingual_field_value($component['content']['image']);

                    $component['content']['image'] = new ImageService($image);
                    $component['content']['signature'] = new MultilingualService($component['content']['signature'] ?? null);
                    break;

                case 'Faq':
                    $component['content']['faq'] = collect(config('system.faq.categories'))->map(function ($category) {
                        $category['title'] = new MultilingualService($category['title']);
                        $category['heading'] = new MultilingualService($category['heading']);

                        $category['items'] = collect($category['items'])->map(function ($item) {
                            $item['question'] = new MultilingualService($item['question']);
                            $item['answer'] = new MultilingualService($item['answer']);

                            return $item;
                        });

                        return $category;
                    });
                    break;

                case 'Person':
                    $component['content']['job'] = new MultilingualService($component['content']['job']);
                    $component['content']['name'] = new MultilingualService($component['content']['name']);
                    $component['content']['about'] = new MultilingualService($component['content']['about']);
                    $component['content']['image'] = new ImageService($component['content']['image']);
                    break;

                case 'Contacts':
                    $component['content'] = [
                        'company-name' => config('system.contacts.company-name'),
                        'address' => config('system.contacts.address'),
                        'registration-numbers' => config('system.contacts.registration-numbers'),
                        'phone' => config('system.contacts.phone'),
                        'email' => config('system.contacts.email'),
                        'opening-hours' => collect(explode("\n", config('system.contacts.opening-hours')))->map(fn($v) => trim($v)),
                        'whatsapp' => config('system.contacts.whatsapp'),
                        'telegram' => config('system.contacts.telegram'),
                        'linkedin' => config('system.contacts.linkedin'),
                        'instagram' => config('system.contacts.instagram'),
                        'vcf' => config('system.contacts.vcf'),
                    ];

                    break;

                case 'ContactCenter':
                    $component['content']['pre_heading'] = new MultilingualService($component['content']['pre_heading']);
                    $component['content']['heading']['text'] = new MultilingualService($component['content']['heading']['text']);
                    $component['content']['text'] = new MultilingualService($component['content']['text']);
                    $component['content']['form'] = Form::where('is_published', true)->find($component['content']['form_id']);
                    break;

                case 'Services':
                    $component['content']['services'] = Service::published()->get();
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
