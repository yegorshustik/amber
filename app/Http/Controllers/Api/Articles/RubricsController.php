<?php

namespace App\Http\Controllers\Api\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Articles\RubricStoreRequest;
use App\Http\Resources\Api\Articles\RubricResource;
use App\Http\Resources\Api\Articles\RubricsCollection;
use App\Models\Articles\Rubric;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RubricsController extends Controller
{
    public function index(Request $request): RubricsCollection
    {
        $query = Rubric::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->when(
                $request->input('sortBy'),
                fn (Builder $builder) => $builder->orderBy($request->input('sortBy'), $request->input('sortOrder', 'asc')),
                fn (Builder $builder) => $builder->orderBy('position', 'asc'),
            );

        return new RubricsCollection($query->paginate());
    }

    public function list(): RubricsCollection
    {
        return new RubricsCollection(Rubric::query()->orderBy('position')->get());
    }

    public function store(RubricStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['site_id'] = site()->id;

        return RubricResource::make(Rubric::create($validated));
    }

    public function show(int $id): RubricResource
    {
        return RubricResource::make(Rubric::findOrFail($id));
    }

    public function update(RubricStoreRequest $request, $id): RubricResource
    {
        $validated = $request->validated();

        $rubric = Rubric::findOrFail($id);
        $rubric->update($validated);

        return RubricResource::make($rubric);
    }

    public function destroy($id)
    {
        $rubric = Rubric::findOrFail($id);

        $rubric->delete();

        return response()->json(['data' => null]);
    }

    public function massDestroy(Request $request)
    {
        Rubric::findOrFail($request->input('ids'))->each(fn ($rubric) => $rubric->delete());

        return response()->json(['data' => null]);
    }

    public function sorting(Request $request): JsonResponse
    {
        foreach ($request->input('ids') ?? [] as $position => $id) {
            Rubric::find($id)?->update(['position' => $position]);
        }

        return response()->json(['data' => null]);
    }
}
