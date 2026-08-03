<?php

namespace App\Http\Requests\Api\Stores;

use App\Rules\Api\RequiredMultilingualRule;
use Illuminate\Foundation\Http\FormRequest;

class CityStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'title' => new RequiredMultilingualRule,
            'is_published' => 'sometimes',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        $validated['is_published'] ??= false;

        return $validated;
    }
}
