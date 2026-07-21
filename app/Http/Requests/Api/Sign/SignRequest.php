<?php

namespace App\Http\Requests\Api\Sign;

use Illuminate\Foundation\Http\FormRequest;

class SignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ];
    }
}
