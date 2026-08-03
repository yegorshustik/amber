<?php

namespace App\Http\Controllers\Api\Stores;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Stores\CityStoreRequest;
use App\Http\Resources\Api\Stores\CitiesCollection;
use App\Http\Resources\Api\Stores\CityResource;
use App\Models\Stores\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(Request $request): CitiesCollection
    {
        $query = City::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->when(
                $request->input('sortBy'),
                fn (Builder $builder) => $builder->orderBy($request->input('sortBy'), $request->input('sortOrder', 'asc')),
                fn (Builder $builder) => $builder->orderBy('position', 'asc'),
            );

        return new CitiesCollection($query->paginate());
    }

    public function store(CityStoreRequest $request)
    {
        $validated = $request->validated();

        return CityResource::make(City::create($validated));
    }

    public function show(int $id): CityResource
    {
        return CityResource::make(City::findOrFail($id));
    }

    public function update(CityStoreRequest $request, $id): CityResource
    {
        $validated = $request->validated();

        $property = City::findOrFail($id);
        $property->update($validated);

        return CityResource::make($property);
    }

    public function destroy($id)
    {
        $property = City::findOrFail($id);

        $property->delete();

        return response()->json(['data' => null]);
    }

    public function sorting(Request $request): JsonResponse
    {
        foreach ($request->input('ids') ?? [] as $position => $id) {
            City::find($id)?->update(['position' => $position]);
        }

        return response()->json(['data' => null]);
    }
}
