<?php

namespace App\Http\Controllers\Api\Filemanager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Filemanager\FileListingRequest;
use App\Http\Requests\Api\Filemanager\FileRequest;
use App\Http\Requests\Api\Filemanager\FilesUploadRequest;
use App\Http\Resources\Api\Filemanager\FileResource;
use App\Models\Filemanager\Directory;
use App\Models\Filemanager\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(FileListingRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $directory = Directory::findOrFail($validated['parent_id']);

        return FileResource::collection($directory->files()->get());
    }

    public function file(Request $request): AnonymousResourceCollection|FileResource
    {
        $id = $request->input('id');

        abort_unless($id, Response::HTTP_NOT_FOUND);

        $id = explode(',', $id);

        $file = File::findOrFail($id);

        if ($file->count() === 1) {
            return new FileResource($file->first());
        }

        return FileResource::collection($file);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        abort_unless($request->input('query'), Response::HTTP_NOT_FOUND);

        return FileResource::collection(File::search($request->input('query'))->get());
    }

    public function remove(FileRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $files = File::find($validated['id']);

        $directory = Directory::findOrFail($validated['parent_id']);

        $files->each(fn (File $file) => $file->delete());

        return FileResource::collection($directory->files()->get());
    }

    public function upload(FilesUploadRequest $request): AnonymousResourceCollection
    {
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');

        $validated = $request->validated();

        $directory = Directory::findOrFail($validated['parent_id']);

        foreach ($validated['files'] as $file) {
            /**
             * @var UploadedFile $file
             * @var Directory $directory
             */
            $hash = md5_file($file->getRealPath());

            $directory->files()->updateOrCreate([
                'parent_id' => $directory->id,
                'hash' => $hash,
            ], [
                'hash' => $hash,
                'name' => $file->getClientOriginalName(),
                'file_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'path' => $file->storeAs(
                    substr($hash, 0, 3).'/'.substr($hash, 3, 2),
                    Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension()
                ),
            ]);
        }

        return FileResource::collection($directory->files()->get());
    }
}
