<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceStoreRequest;
use App\Http\Resources\Api\ServiceResource;
use App\Http\Resources\Api\ServicesCollection;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(Request $request): ServicesCollection
    {
        $query = Service::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->when(
                $request->input('sortBy'),
                fn (Builder $builder) => $builder->orderBy($request->input('sortBy'), $request->input('sortOrder', 'asc')),
                fn (Builder $builder) => $builder->orderBy('position', 'asc'),
            );

        return new ServicesCollection($query->paginate());
    }

    public function list(): ServicesCollection
    {
        return new ServicesCollection(Service::query()->orderBy('position')->get());
    }

    public function store(ServiceStoreRequest $request)
    {
        $validated = $request->validated();

        return ServiceResource::make(Service::create($validated));
    }

    public function show(int $id): ServiceResource
    {
        return ServiceResource::make(Service::findOrFail($id));
    }

    public function update(ServiceStoreRequest $request, $id): ServiceResource
    {
        $validated = $request->validated();

        $service = Service::findOrFail($id);
        $service->update($validated);

        return ServiceResource::make($service);
    }

    public function destroy($id): ServicesCollection
    {
        $service = Service::findOrFail($id);

        $service->delete();

        return $this->list();
    }

    public function massDestroy(Request $request): ServicesCollection
    {
        Service::findOrFail($request->input('ids'))->each(fn ($service) => $service->delete());

        return $this->list();

    }

    public function sorting(Request $request): ServicesCollection
    {
        foreach ($request->input('ids') ?? [] as $position => $id) {
            Service::find($id)?->update(['position' => $position]);
        }

        return $this->list();
    }
}
