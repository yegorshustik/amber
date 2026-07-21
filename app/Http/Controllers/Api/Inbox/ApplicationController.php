<?php

namespace App\Http\Controllers\Api\Inbox;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inbox\ApplicationStoreRequest;
use App\Http\Resources\Api\Inbox\ApplicationsCollection;
use App\Http\Resources\Api\Inbox\FieldResource;
use App\Models\Inbox\Application;
use App\Models\Inbox\Field;
use App\Models\Inbox\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index(Request $request): ApplicationsCollection
    {
        $form = Form::findOrFail($request->input('form_id'));
        $applications = $this->prepareQuery($form, $request);

        return new ApplicationsCollection($applications->paginate());
    }

    public function export(Request $request)
    {
        $form = Form::findOrFail($request->input('form_id'));
        $applications = $this->prepareQuery($form, $request);

        $fileName = $form->slug.'-'.now()->format('Y-m-d-his').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($form, $applications) {
            $file = fopen('php://output', 'w');

            $row = [];
            $row[] = __('cms.created_at');

            foreach ($form->fields as $field) {
                $row[] = $field->title;
            }

            fputcsv($file, $row);

            foreach ($applications->get() as $item) {
                $row = [];
                $row[] = $item->created_at;
                foreach ($form->fields as $field) {
                    $content = $item['field_'.$field->id];

                    $row[] = $content;
                }
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(ApplicationStoreRequest $request)
    {
        $validated = $request->validated();

        $form = Form::findOrFail($validated['form_id']);

        $form->createApplication($validated);

        return response()->json(['data' => null]); // FieldResource::make($form->fields()->create($validated));
    }

    public function show(int $id): FieldResource
    {
        return FieldResource::make(Field::findOrFail($id));
    }

    public function destroy($id)
    {
        $application = Application::findOrFail($id);

        $application->delete();

        return response()->json(['data' => null]);
    }

    public function massDestroy(Request $request)
    {
        $form = Form::findOrFail($request->input('form_id'));

        $form->applications()->findOrFail($request->input('ids'))->each(fn (Application $application) => $application->delete());

        return response()->json(['data' => null]);
    }

    private function prepareQuery(Form $form, Request $request)
    {
        $subquery = [];

        foreach ($form->fields as $field) {
            $subquery[] = DB::raw('(
                SELECT inbox_application_fields.content
                FROM inbox_application_fields
                WHERE inbox_application_fields.application_id = inbox_applications.id AND
                      inbox_application_fields.form_id = inbox_applications.form_id AND
                      inbox_application_fields.field_id = '.$field->id.'
            ) AS field_'.$field->id);
        }

        $cols = [
            'inbox_applications.id',
            'inbox_applications.created_at',
            'inbox_applications.updated_at',
        ];

        $applications = Application::query()
            ->select(array_merge($cols, $subquery))
            ->where('form_id', $form->id)
            ->orderBy('inbox_applications.created_at', 'desc');

        return $applications;
    }
}
