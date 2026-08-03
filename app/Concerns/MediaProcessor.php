<?php

namespace App\Concerns;

use App\Services\Api\ImageService;
use App\Services\Api\ImagesService;
use App\Services\Api\MultilingualService;
use App\Services\Localization;
use Illuminate\Http\Request;

trait MediaProcessor
{
    public static function bootMediaProcessor(): void
    {
        static::saved(function ($model) {
            $request = request();

            if (! $request || ! method_exists($model, 'getMediaMapping')) {
                return;
            }

            $model->syncFromRequest($request);
        });
    }

    public function getMediaData($key)
    {
        $mapping = $this->getMediaMapping();

        if (array_key_exists($key, $mapping)) {
            if ($mapping[$key] == 'single') {
                $media = $this->getFirstMedia($key);

                $image = new ImageService($media ? [
                    'src' => [
                        'id' => $media->id,
                        'path' => $media->getPath(),
                        'url' => $media->getUrl(),
                    ],
                    'alt' => new MultilingualService($media->getCustomProperty('alt')),
                    'title' => new MultilingualService($media->getCustomProperty('title')),
                ] : []);
                return $image;
            } elseif ($mapping[$key] == 'single_localized') {
                $data = [];
                foreach ((new Localization)->available() as $locale) {
                    $media = $this->getFirstMedia($key.'_'.$locale['locale']);

                    $data[$locale['locale']] = new ImageService($media ? [
                        'src' => [
                            'id' => $media->id,
                            'path' => $media->getPath(),
                            'url' => $media->getUrl(),
                        ],
                        'alt' => $media->getCustomProperty('alt'),
                        'title' => $media->getCustomProperty('title'),
                    ] : []);
                }

                return new MultilingualService($data);
            } elseif ($mapping[$key] == 'multiple') {
                $data = [];

                foreach ($this->getMedia($key) as $media) {
                    $data[] = [
                        'src' => [
                            'id' => $media->id,
                            'path' => $media->getPath(),
                            'url' => $media->getUrl(),
                        ],
                        'alt' => $media->getCustomProperty('alt'),
                        'title' => $media->getCustomProperty('title'),
                    ];
                }

                return new ImagesService($data);
            } elseif ($mapping[$key] == 'multiple_localized') {
                $data = [];
                foreach ((new Localization)->available() as $locale) {
                    $images = [];

                    foreach ($this->getMedia($key.'_'.$locale['locale']) as $media) {
                        $images[] = [
                            'src' => [
                                'id' => $media->id,
                                'path' => $media->getPath(),
                                'url' => $media->getUrl(),
                            ],
                            'alt' => $media->getCustomProperty('alt'),
                            'title' => $media->getCustomProperty('title'),
                        ];
                    }

                    $data[$locale['locale']] = new ImagesService($images);
                }

                return new MultilingualService($data);
            }
        }
    }

    public function syncFromRequest($request): void
    {
        foreach ($this->getMediaMapping() as $inputKey => $type) {
            if (! $request->has($inputKey)) {
                continue;
            }

            if ($type == 'single') {
                $this->syncSingle($request, $inputKey);
            } elseif ($type == 'single_localized') {
                $this->syncSingleLocalized($request, $inputKey);
            } elseif ($type == 'multiple') {
                $this->syncMultiple($request, $inputKey);
            } elseif ($type == 'multiple_localized') {
                $this->syncMultipleLocalized($request, $inputKey);
            }
        }
    }

    protected function syncMultipleLocalized(Request $request, string $collection): void
    {
        $dataCollectionLocalized = $request->input($collection);
        foreach ((new Localization)->available() as $locale) {
            $dataCollection = $dataCollectionLocalized[$locale['locale']] ?? null;

            $localized_collection = $collection.'_'.$locale['locale'];

            if (! $dataCollection) {
                $this->clearMediaCollection($localized_collection);

                continue;
            }

            $actualMediaList = [];
            foreach ($dataCollection as $data) {
                if ($data['src']['id'] == -1) {
                    $media = $this->addMediaFromDisk($data['src']['path'])->toMediaCollection($localized_collection);
                } else {
                    $media = $this->getMedia($localized_collection)->where('id', $data['src']['id'])->first();
                }

                $media->setCustomProperty('alt', $data['alt'] ?? null);
                $media->setCustomProperty('title', $data['title'] ?? null);
                $media->save();

                $actualMediaList[] = $media->toArray();
            }

            $this->updateMedia($actualMediaList, $localized_collection);
        }
    }

    protected function syncMultiple(Request $request, string $collection): void
    {
        $dataCollection = $request->input($collection);

        if (! $dataCollection) {
            $this->clearMediaCollection($collection);

            return;
        }

        $actualMediaList = [];
        foreach ($dataCollection as $data) {

            if ($data['src']['id'] == -1) {
                $media = $this->addMediaFromDisk($data['src']['path'])->toMediaCollection($collection);
            } else {
                $media = $this->getMedia($collection)->where('id', $data['src']['id'])->first();
            }

            $media->setCustomProperty('alt', $data['alt'] ?? null);
            $media->setCustomProperty('title', $data['title'] ?? null);
            $media->save();

            $actualMediaList[] = $media->toArray();
        }

        $this->updateMedia($actualMediaList, $collection);
    }

    protected function syncSingleLocalized(Request $request, string $collection): void
    {
        $dataCollection = $request->input($collection);

        foreach ((new Localization)->available() as $locale) {
            $data = $dataCollection[$locale['locale']] ?? null;

            $localized_collection = $collection.'_'.$locale['locale'];

            if (! $data) {
                $this->clearMediaCollection($localized_collection);

                continue;
            }

            if ($data['src']['id'] == -1) {
                $this->clearMediaCollection($localized_collection);
                $media = $this->addMediaFromDisk($data['src']['path'])->toMediaCollection($localized_collection);
            } else {
                $media = $this->getFirstMedia($localized_collection);
            }

            $media->setCustomProperty('alt', $data['alt'] ?? null);
            $media->setCustomProperty('title', $data['title'] ?? null);
            $media->save();
        }
    }

    protected function syncSingle(Request $request, string $collection): void
    {
        $data = $request->input($collection);

        if (! $data) {
            $this->clearMediaCollection($collection);

            return;
        }

        if ($data['src']['id'] == -1) {
            $this->clearMediaCollection($collection);
            $media = $this->addMediaFromDisk($data['src']['path'])->toMediaCollection($collection);
        } else {
            $media = $this->getFirstMedia($collection);
        }

        $media->setCustomProperty('alt', $data['alt'] ?? null);
        $media->setCustomProperty('title', $data['title'] ?? null);
        $media->save();
    }
}
