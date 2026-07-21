<?php

namespace App\Http\Controllers\Api\Inbox;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inbox\FieldStoreRequest;
use App\Http\Resources\Api\Inbox\FieldResource;
use App\Http\Resources\Api\Inbox\FieldsCollection;
use App\Models\Inbox\Field;
use App\Models\Inbox\Form;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    public function index(Request $request): FieldsCollection
    {
        $form = Form::findOrFail($request->input('form_id'));

        $query = $form->fields()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['title'], 'like', '%'.$request->input('q').'%'))
            ->orderBy('position', 'asc');

        return new FieldsCollection($query->paginate());
    }

    public function list(Request $request): FieldsCollection
    {
        $form = Form::findOrFail($request->input('form_id'));

        return new FieldsCollection($form->fields()->orderBy('position')->get());
    }

    public function store(FieldStoreRequest $request)
    {
        $validated = $request->validated();
        $form = Form::findOrFail($validated['form_id']);

        return FieldResource::make($form->fields()->create($validated));
    }

    public function show(int $id): FieldResource
    {
        return FieldResource::make(Field::findOrFail($id));
    }

    public function update(FieldStoreRequest $request, $id): FieldResource
    {
        $validated = $request->validated();
        $form = Form::findOrFail($validated['form_id']);

        $field = $form->fields()->findOrFail($id);
        $field->update($validated);

        return FieldResource::make($field);
    }

    public function destroy($id)
    {
        $field = Field::findOrFail($id);

        $field->delete();

        return response()->json(['data' => null]);
    }

    public function massDestroy(Request $request)
    {
        $form = Form::findOrFail($request->input('form_id'));

        $form->fields()->findOrFail($request->input('ids'))->each(fn (Field $field) => $field->delete());

        return response()->json(['data' => null]);
    }

    public function sorting(Request $request): JsonResponse
    {
        $form = Form::findOrFail($request->input('form_id'));

        foreach ($request->input('ids') ?? [] as $position => $id) {
            $form->fields()->find($id)?->update(['position' => $position]);
        }

        return response()->json(['data' => null]);
    }
}
