<?php

namespace App\Observers;

class BaseTreeObserver
{
    public function saving($model): void
    {
        if ($model->url != ($url = $this->generateFullUrl($model))) {
            $model->url = $url;
        }
    }

    public function saved($model): void
    {
        if ($model->isDirty('slug') || $model->isDirty('parent_id')) {
            foreach ($model->descendants as $descendant) {
                $descendant->url = $this->generateFullUrl($model);
                $descendant->saveQuietly();
            }
        }
    }

    public function generateFullUrl($model): string
    {
        $ancestors = $model->ancestors();

        if (in_array(class_basename($model), ['Page'])) {
            $ancestors = $ancestors->site();
        }

        $slugs = $ancestors->pluck('slug')->push($model->slug);

        $filteredSlugs = $slugs->filter(fn ($slug) => $slug !== null);

        return $filteredSlugs->implode('/');
    }
}
