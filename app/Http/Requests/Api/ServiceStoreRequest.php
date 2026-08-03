<?php

namespace App\Http\Requests\Api;

use App\Rules\Api\RequiredMultilingualRule;
use App\Services\Localization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'title' => new RequiredMultilingualRule,
            'slug' => [
                Rule::unique('services')->when($this->route('service'), fn ($rule) => $rule->ignore($this->route('service'))),
            ],
            'is_published' => 'sometimes',
            'seo' => 'sometimes',
            'content' => 'sometimes',
            'details' => 'sometimes',
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
