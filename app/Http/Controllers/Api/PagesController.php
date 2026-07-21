<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Pages\PageStoreRequest;
use App\Http\Resources\Api\Pages\PageResource;
use App\Models\Page;
use App\Services\Localization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PagesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        switch ($request->input('action')) {
            /*
             * Load tree
             */
            case 'load-tree':
                $root = Page::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->whereIsRoot()->first();

                $siteRoot = Page::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->whereParentId($root->id)->site()->first();

                if (! $siteRoot) {
                    $siteRoot = Page::create([
                        'site_id' => site()->id,
                        'parent_id' => $root->id,
                        'title' => (new Localization)->fillLocalized('Home'),
                    ]);
                }

                $tree = Page::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->site()->orderBy('position')->get()->toTree(root: $siteRoot)->toArray();

                array_unshift($tree, $siteRoot->toArray());

                return response()->json([
                    'data' => $tree,
                ]);

                /*
                 * Load flat tree
                 */
            case 'load-flat-tree':
                $root = Page::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->whereIsRoot()->first();

                $siteRoot = Page::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->whereParentId($root->id)->site()->first();

                $tree = Page::select([
                    'id',
                    'parent_id',
                    'title',
                    'position',
                ])->orderBy('position')->get()->toFlatTree(root: $siteRoot)->toArray();

                array_unshift($tree, $siteRoot->toArray());

                return response()->json([
                    'data' => $tree,
                ]);

                /*
                 * Move tree node
                 */
            case 'move':
                $node = Page::site()->findOrFail($request->input('node'));

                $parent = $request->input('parent')
                    ? Page::site()->findOrFail($request->input('parent'))
                    : Page::site()->whereParentId(1)->firstOrFail();

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

    public function show(int $id): PageResource
    {
        return new PageResource(Page::site()->findOrFail($id));
    }

    public function store(PageStoreRequest $request): PageResource
    {
        $validated = $request->validated();

        if (! $validated['parent_id']) {
            $root = Page::whereIsRoot()->first();
            $siteRoot = Page::select([
                'id',
                'parent_id',
                'title',
                'position',
            ])->whereParentId($root->id)->site()->first();
            $validated['parent_id'] = $siteRoot->id;
        }

        $position = Page::whereParentId($validated['parent_id'])->max('position') + 1;

        if ($validated['id']) {
            unset($validated['parent_id']);

            $page = Page::findOrFail($validated['id']);
            $page->update($validated);
        } else {
            $page = Page::create($validated + ['site_id' => site()->id, 'position' => $position]);
        }

        return new PageResource($page);
    }

    public function destroy(int $id): JsonResponse
    {
        $page = Page::site()->findOrFail($id);

        $page->delete();

        Page::fixTree();

        return \response()->json();
    }
}
