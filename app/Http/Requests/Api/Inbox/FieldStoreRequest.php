<?php

namespace App\Http\Requests\Api\Inbox;

use Illuminate\Foundation\Http\FormRequest;

class FieldStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes',
            'form_id' => 'required',
            'type' => 'sometimes',
            'title' => 'sometimes',
            'placeholder' => 'sometimes',
            'is_published' => 'sometimes',
            'is_required' => 'sometimes',
            'is_fullsize' => 'sometimes',
            'in_table' => 'sometimes',
            'options' => 'sometimes',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        $validated['is_published'] ??= false;
        $validated['is_required'] ??= false;
        $validated['is_fullsize'] ??= false;
        $validated['in_table'] ??= false;

        return $validated;
    }
}
