<?php

namespace App\Http\Requests\Api\Filemanager;

use Illuminate\Foundation\Http\FormRequest;

class TempFilesUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'files' => 'sometimes|array|min:1',
            'files.*' => 'required|file',
        ];
    }
}
