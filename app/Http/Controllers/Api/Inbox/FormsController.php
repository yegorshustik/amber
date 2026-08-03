<?php

namespace App\Http\Controllers\Api\Inbox;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inbox\FormStoreRequest;
use App\Http\Resources\Api\Inbox\FormResource;
use App\Http\Resources\Api\Inbox\FormsCollection;
use App\Models\Inbox\Form;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormsController extends Controller
{
    public function index(Request $request): FormsCollection
    {
        $query = Form::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->orderBy('position', 'asc');

        return new FormsCollection($query->paginate());
    }

    public function list(): FormsCollection
    {
        return new FormsCollection(Form::query()->orderBy('position')->get());
    }

    public function store(FormStoreRequest $request)
    {
        $validated = $request->validated();

        return FormResource::make(Form::create($validated));
    }

    public function show(int $id): FormResource
    {
        return FormResource::make(Form::findOrFail($id));
    }

    public function update(FormStoreRequest $request, $id): FormResource
    {
        $validated = $request->validated();

        $form = Form::findOrFail($id);
        $form->update($validated);

        return FormResource::make($form);
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);

        $form->delete();

        return response()->json(['data' => null]);
    }

    public function massDestroy(Request $request)
    {
        Form::findOrFail($request->input('ids'))->each(fn (Form $form) => $form->delete());

        return response()->json(['data' => null]);
    }

    public function sorting(Request $request): JsonResponse
    {
        foreach ($request->input('ids') ?? [] as $position => $id) {
            Form::find($id)?->update(['position' => $position]);
        }

        return response()->json(['data' => null]);
    }
}
