<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CatalogStoreRequest;
use App\Http\Resources\Api\CatalogResource;
use App\Http\Resources\Api\CatalogsCollection;
use App\Models\Catalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request): CatalogsCollection
    {
        $query = Catalog::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->when(
                $request->input('sortBy'),
                fn (Builder $builder) => $builder->orderBy($request->input('sortBy'), $request->input('sortOrder', 'asc')),
                fn (Builder $builder) => $builder->orderBy('position', 'asc'),
            );

        return new CatalogsCollection($query->paginate());
    }

    public function list(): CatalogsCollection
    {
        return new CatalogsCollection(Catalog::query()->orderBy('position')->get());
    }

    public function store(CatalogStoreRequest $request)
    {
        $validated = $request->validated();

        return CatalogResource::make(Catalog::create($validated));
    }

    public function show(int $id): CatalogResource
    {
        return CatalogResource::make(Catalog::findOrFail($id));
    }

    public function update(CatalogStoreRequest $request, $id): CatalogResource
    {
        $validated = $request->validated();

        $catalog = Catalog::findOrFail($id);
        $catalog->update($validated);

        return CatalogResource::make($catalog);
    }

    public function destroy($id): CatalogsCollection
    {
        $catalog = Catalog::findOrFail($id);

        $catalog->delete();

        return $this->list();
    }

    public function massDestroy(Request $request): CatalogsCollection
    {
        Catalog::findOrFail($request->input('ids'))->each(fn ($catalog) => $catalog->delete());

        return $this->list();

    }

    public function sorting(Request $request): CatalogsCollection
    {
        foreach ($request->input('ids') ?? [] as $position => $id) {
            Catalog::find($id)?->update(['position' => $position]);
        }

        return $this->list();
    }
}
