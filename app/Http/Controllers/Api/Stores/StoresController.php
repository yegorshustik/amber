<?php

namespace App\Http\Controllers\Api\Stores;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Stores\StoreStoreRequest;
use App\Http\Resources\Api\Stores\StoresCollection;
use App\Http\Resources\Api\Stores\StoreResource;
use App\Models\Stores\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function index(Request $request): StoresCollection
    {
        $query = Store::query()
            ->with('sites')
            ->where(function (Builder $builder) {
                $builder->whereHas('sites', fn (Builder $builder) => $builder->where('id', site()->id))
                    ->orWhereDoesntHave('sites');
            })
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->when(
                $request->input('sortBy'),
                fn (Builder $builder) => $builder->orderBy($request->input('sortBy'), $request->input('sortOrder', 'asc')),
                fn (Builder $builder) => $builder->orderBy('position', 'asc'),
            );

        return new StoresCollection($query->paginate());
    }

    public function list(Request $request): StoresCollection
    {
        $limit = $request->input('limit', 10);

        return new StoresCollection(Store::query()->where(function (Builder $builder) {
            $builder->whereHas('sites', fn (Builder $builder) => $builder->where('id', site()->id))
                ->orWhereDoesntHave('sites');
        })->limit($limit)->orderBy('position')->get());
    }

    public function store(StoreStoreRequest $request)
    {
        $validated = $request->validated();

        $validated['latitude'] = $validated['coordinates']['latitude'] ?? null;
        $validated['longitude'] = $validated['coordinates']['longitude'] ?? null;

        $store = Store::create($validated);
        $store->sites()->sync($validated['sites'] ?? []);

        return StoreResource::make($store);
    }

    public function show(int $id): StoreResource
    {
        return StoreResource::make(Store::findOrFail($id));
    }

    public function update(StoreStoreRequest $request, $id): StoreResource
    {
        $validated = $request->validated();

        $validated['latitude'] = $validated['coordinates']['latitude'] ?? null;
        $validated['longitude'] = $validated['coordinates']['longitude'] ?? null;

        $store = Store::findOrFail($id);
        $store->update($validated);
        $store->sites()->sync($validated['sites'] ?? []);

        return StoreResource::make($store);
    }

    public function destroy($id)
    {
        $property = Store::findOrFail($id);

        $property->delete();

        return response()->json(['data' => null]);
    }

    public function massDestroy(Request $request)
    {
        Store::findOrFail($request->input('ids'))->each(fn ($property) => $property->delete());

        return response()->json(['data' => null]);
    }

    public function sorting(Request $request): JsonResponse
    {
        foreach ($request->input('ids') ?? [] as $position => $id) {
            Store::find($id)?->update(['position' => $position]);
        }

        return response()->json(['data' => null]);
    }
}
