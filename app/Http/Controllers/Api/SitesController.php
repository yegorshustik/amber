<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SiteStoreRequest;
use App\Http\Resources\Api\SiteResource;
use App\Http\Resources\Api\SitesCollection;
use App\Models\Site;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SitesController extends Controller
{
    public function index(Request $request): SitesCollection
    {
        $query = Site::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->when(
                $request->input('sortBy'),
                fn (Builder $builder) => $builder->orderBy($request->input('sortBy'), $request->input('sortOrder', 'asc')),
                fn (Builder $builder) => $builder->orderBy('position', 'asc'),
            );

        return new SitesCollection($query->paginate());
    }

    public function list(): SitesCollection
    {
        return new SitesCollection(Site::query()->orderBy('position')->get());
    }

    public function store(SiteStoreRequest $request)
    {
        $validated = $request->validated();

        return SiteResource::make(Site::create($validated));
    }

    public function show(int $id): SiteResource
    {
        return SiteResource::make(Site::findOrFail($id));
    }

    public function update(SiteStoreRequest $request, $id): SiteResource
    {
        $validated = $request->validated();

        $site = Site::findOrFail($id);
        $site->update($validated);

        return SiteResource::make($site);
    }

    public function destroy($id): SitesCollection
    {
        $site = Site::findOrFail($id);

        $site->delete();

        return $this->list();
    }

    public function massDestroy(Request $request): SitesCollection
    {
        Site::findOrFail($request->input('ids'))->each(fn ($site) => $site->delete());

        return $this->list();

    }

    public function sorting(Request $request): SitesCollection
    {
        foreach ($request->input('ids') ?? [] as $position => $id) {
            Site::find($id)?->update(['position' => $position]);
        }

        return $this->list();
    }
}
