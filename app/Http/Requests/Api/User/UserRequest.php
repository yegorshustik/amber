<?php

namespace App\Http\Requests\Api\User;

use App\Enums\Users\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => 'sometimes',
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'phone' => 'sometimes',
            'company_name' => 'sometimes',
            'company_erdpo' => 'sometimes',
            'company_legal_address' => 'sometimes',
            'documents' => 'sometimes',
            'is_activated' => 'boolean:true,false',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->when($this->route('user'), fn ($rule) => $rule->ignore($this->route('user'))),
            ],
            'password' => 'confirmed',
            'status' => ['required', new Enum(UserStatus::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function validationData()
    {
        return $this->mergeIfMissing([
            'is_activated' => 0,
        ])->all();
    }
}
