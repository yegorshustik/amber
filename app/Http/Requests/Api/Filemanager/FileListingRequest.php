<?php

namespace App\Http\Requests\Api\Filemanager;

use Illuminate\Foundation\Http\FormRequest;

class FileListingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => 'required|integer',
        ];
    }
}
