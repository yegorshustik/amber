<?php

namespace App\Http\Requests\Api\Inbox;

use App\Enums\Inbox\FieldType;
use App\Models\Inbox\Field;
use App\Models\Inbox\Form;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationStoreRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var Form $form */
        $form = Form::find($this->request->get('form_id'));

        $rules = [
            'form_id' => 'required',
        ];

        foreach ($form->fields as $field) {
            /** @var Field $field */
            if ($field->is_required) {
                $rules['field_'.$field->id][] = 'required';
            }
            if ($field->is_required && $field->type == FieldType::EMAIL) {
                $rules['field_'.$field->id][] = 'email';
            }
            if (! $field->is_required) {
                $rules['field_'.$field->id][] = 'sometimes';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        /** @var Form $form */
        $form = Form::find($this->request->get('form_id'));

        $messages = [];

        foreach ($form->fields as $field) {
            /** @var Field $field */
            if ($field->is_required) {
                $messages['field_'.$field->id.'.required'] = __('validation.required', ['attribute' => $field->title]);
            }
            if ($field->is_required && $field->type == FieldType::EMAIL) {
                $messages['field_'.$field->id.'.email'] = __('validation.email', ['attribute' => $field->title]);
            }
        }

        return $messages;
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        return $validated;
    }
}
