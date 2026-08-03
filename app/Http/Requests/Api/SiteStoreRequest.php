<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                Rule::unique('sites')->when($this->route('site'), fn ($rule) => $rule->ignore($this->route('site'))),
            ],
            'is_published' => 'sometimes',

            'domain' => [
                'required',
                Rule::unique('sites')->when($this->route('site'), fn ($rule) => $rule->ignore($this->route('site'))),
            ],
            'domain_alternative' => 'sometimes',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        $validated['is_published'] ??= false;

        return $validated;
    }
}
