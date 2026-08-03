<?php

namespace App\Http\Requests\Api;

use App\Rules\Api\RequiredMultilingualRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'published_at' => 'sometimes',
            'image' => 'sometimes',
            'name' => new RequiredMultilingualRule,
            'job' => 'sometimes',
            'is_published' => 'sometimes',
            'content' => 'sometimes',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        $validated['is_published'] ??= false;

        return $validated;
    }
}
