<?php

namespace App\Http\Requests\Api\Stores;

use App\Rules\Api\RequiredMultilingualRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'city_id' => 'sometimes',
            'form_id' => 'sometimes',
            'sites' => 'sometimes',
            'images' => 'sometimes',
            'title' => new RequiredMultilingualRule,
            'is_published' => 'sometimes',
            'content' => 'sometimes',
            'address' => 'sometimes',
            'contacts' => 'sometimes',
            'coordinates' => 'sometimes',
            'latitude' => 'sometimes',
            'longitude' => 'sometimes',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        $validated['is_published'] ??= false;

        return $validated;
    }
}
