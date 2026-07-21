<?php

namespace App\Http\Controllers\Api\Filemanager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Filemanager\DirectoryStoreRequest;
use App\Http\Requests\Api\Filemanager\DirectoryUpdateRequest;
use App\Http\Resources\Api\Filemanager\DirectoryResource;
use App\Models\Filemanager\Directory;
use App\Models\Filemanager\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DirectoryController extends Controller
{
    public function index(Request $request): JsonResponse|DirectoryResource
    {
        switch ($request->input('action')) {
            /*
             * Load tree
             */
            case 'load-tree':
                $root = Directory::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->whereIsRoot()->first();

                $tree = Directory::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->orderBy('position')->get()->toTree(root: $root)->toArray();

                array_unshift($tree, $root->toArray());

                return response()->json([
                    'data' => $tree,
                ]);

                /*
                 * Load flat tree
                 */
            case 'load-flat-tree':
                $root = Directory::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->whereIsRoot()->first();

                $tree = Directory::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->orderBy('position')->get()->toFlatTree(root: $root)->toArray();

                array_unshift($tree, $root->toArray());

                return response()->json([
                    'data' => $tree,
                ]);

                /*
                 * Move tree node
                 */
            case 'move':
                $node = Directory::findOrFail($request->input('node'));

                $parent = $request->input('parent')
                    ? Directory::findOrFail($request->input('parent'))
                    : Directory::whereIsRoot()->firstOrFail();

                $node->appendToNode($parent)->save();

                $siblings = explode(',', $request->input('siblings'));
                foreach ($siblings as $i => $sibling) {
                    $parent->children()->find($sibling)?->update([
                        'position' => $i + 1,
                    ]);
                }

                return response()->json([]);

            default:
                abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function store(DirectoryStoreRequest $request): DirectoryResource
    {
        $validated = $request->validated();

        $position = Directory::whereParentId($request->input('parent_id'))->max('position') + 1;

        $directory = Directory::create($validated + ['position' => $position]);

        return new DirectoryResource($directory);
    }

    public function update(DirectoryUpdateRequest $request): DirectoryResource
    {
        $validated = $request->validated();
        $directory = Directory::findOrFail($validated['id']);

        $directory->update([
            'title' => $validated['title'],
        ]);

        return new DirectoryResource($directory);
    }

    public function remove(Request $request): JsonResponse
    {
        $directory = Directory::findOrFail($request->input('id'));

        $directory->descendants()->each(fn (Directory $item) => $item->files->each(fn (File $file) => $file->delete()));

        $directory->files->each(fn (File $file) => $file->delete());
        $directory->delete();

        Directory::fixTree();

        return \response()->json();
    }
}
