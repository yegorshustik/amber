<?php

namespace App\Http\Requests\Api\Articles;

use App\Rules\Api\RequiredMultilingualRule;
use App\Services\Localization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ArticleStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'title' => new RequiredMultilingualRule,
            'image' => 'sometimes',
            'slug' => 'sometimes',
            'is_published' => 'sometimes',
            'published_at' => 'required|date_format:Y-m-d H:i:s',
            'seo' => 'sometimes',
            'tags' => 'sometimes',
            'rubrics' => 'required|array|min:1',
            'announcement' => 'sometimes',
            'content' => 'sometimes',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        $validated['slug'] ??= $validated['title'][(new Localization)->current()['locale']];
        $validated['slug'] = Str::slug($validated['slug']);

        $validated['is_published'] ??= false;

        return $validated;
    }
}
