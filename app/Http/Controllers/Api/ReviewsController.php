<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewStoreRequest;
use App\Http\Resources\Api\ReviewsCollection;
use App\Http\Resources\Api\ReviewResource;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(Request $request): ReviewsCollection
    {
        $query = Review::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->when(
                $request->input('sortBy'),
                fn (Builder $builder) => $builder->orderBy($request->input('sortBy'), $request->input('sortOrder', 'asc')),
                fn (Builder $builder) => $builder->orderBy('position', 'asc'),
            );

        return new ReviewsCollection($query->paginate());
    }

    public function list(Request $request): ReviewsCollection
    {
        $limit = $request->input('limit', 10);

        return new ReviewsCollection(Review::query()->limit($limit)->orderBy('position')->get());
    }

    public function store(ReviewStoreRequest $request)
    {
        $validated = $request->validated();

        return ReviewResource::make(Review::create($validated));
    }

    public function show(int $id): ReviewResource
    {
        return ReviewResource::make(Review::findOrFail($id));
    }

    public function update(ReviewStoreRequest $request, $id): ReviewResource
    {
        $validated = $request->validated();

        $property = Review::findOrFail($id);
        $property->update($validated);

        return ReviewResource::make($property);
    }

    public function destroy($id)
    {
        $property = Review::findOrFail($id);

        $property->delete();

        return response()->json(['data' => null]);
    }

    public function massDestroy(Request $request)
    {
        Review::findOrFail($request->input('ids'))->each(fn ($property) => $property->delete());

        return response()->json(['data' => null]);
    }

    public function sorting(Request $request): JsonResponse
    {
        foreach ($request->input('ids') ?? [] as $position => $id) {
            Review::find($id)?->update(['position' => $position]);
        }

        return response()->json(['data' => null]);
    }
}
