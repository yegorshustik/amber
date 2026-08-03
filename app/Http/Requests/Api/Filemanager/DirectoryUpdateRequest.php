<?php

namespace App\Http\Requests\Api\Filemanager;

use Illuminate\Foundation\Http\FormRequest;

class DirectoryUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|exists:filemanager_directories,id',
            'title' => 'required|string',
        ];
    }
}
