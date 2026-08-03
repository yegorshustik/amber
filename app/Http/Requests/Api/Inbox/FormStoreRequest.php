<?php

namespace App\Http\Requests\Api\Inbox;

use App\Rules\Api\RequiredMultilingualRule;
use App\Services\Localization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class FormStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'title' => new RequiredMultilingualRule,
            'slug' => 'sometimes',
            'is_published' => 'sometimes',
            'options' => 'sometimes',
            'recipients' => 'sometimes',
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
