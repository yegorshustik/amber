<?php

namespace App\Http\Requests\Api;

use App\Rules\Api\RequiredMultilingualRule;
use App\Services\Localization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CatalogStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'type' => 'sometimes',
            'title' => new RequiredMultilingualRule,
            'is_published' => 'sometimes',
            'is_visible' => 'sometimes',
            'slug' => 'sometimes',
            'country' => 'sometimes',
            'city' => 'sometimes',
            'short_details' => 'sometimes',
            'details' => 'sometimes',
            'age_range' => 'sometimes',
            'gender' => 'sometimes',
            'boarding' => 'sometimes',
            'curriculum' => 'sometimes',
            'size' => 'sometimes',
            'campus_style' => 'sometimes',
            'programs' => 'sometimes',
            'degrees' => 'sometimes',
            'acceptance' => 'sometimes',
            'established' => 'sometimes',
            'image' => 'sometimes',
            'pre_heading' => 'sometimes',
            'heading' => 'sometimes',
            'content' => 'sometimes',
            'faq' => 'sometimes',
            'seo' => 'sometimes',
            'position' => 'sometimes',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        $validated['slug'] ??= $validated['title'][(new Localization)->current()['locale']];
        $validated['slug'] = Str::slug($validated['slug']);

        $validated['is_published'] ??= false;
        $validated['is_visible'] ??= false;

        return $validated;
    }
}
