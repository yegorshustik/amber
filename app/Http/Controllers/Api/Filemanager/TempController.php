<?php

namespace App\Http\Controllers\Api\Filemanager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Filemanager\TempFilesUploadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TempController extends Controller
{
    public function upload(TempFilesUploadRequest $request): JsonResponse
    {
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');

        $validated = $request->validated();

        $response = [];
        foreach ($validated['files'] as $file) {
            /**
             * @var UploadedFile $file
             */
            $path = $file->storeAs(
                '___temp',
                Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension()
            );

            $response[] = [
                'id' => -1,
                'name' => $file->getClientOriginalName(),
                'file_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
                'extension' => $file->getClientOriginalExtension(),
                'url' => Storage::url($path),
            ];
        }

        return response()->json([
            'data' => $response,
        ]);
    }
}
