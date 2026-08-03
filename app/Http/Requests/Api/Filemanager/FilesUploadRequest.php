<?php

namespace App\Http\Requests\Api\Filemanager;

use Illuminate\Foundation\Http\FormRequest;

class FilesUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => 'required|integer',
            'files' => 'sometimes|array|min:1',
            'files.*' => 'required|file',
        ];
    }
}
