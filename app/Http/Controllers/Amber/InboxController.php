<?php

namespace App\Http\Controllers\Amber;

use App\Enums\Inbox\FieldType;
use App\Http\Controllers\Controller;
use App\Models\Inbox\Application;
use App\Models\Inbox\Field;
use App\Models\Inbox\Form;
use App\Rules\CaptchaRule;
use App\Services\Api\MultilingualService;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(string $slug, Request $request)
    {
        $form = Form::whereSlug($slug)->firstorFail();

        $rules = [
            'g-recaptcha-response' => [
                'required',
                new CaptchaRule,
            ],
        ];

        foreach ($form->fields as $field) {
            /** @var Field $field */
            if ($field->is_required) {
                $rules['field_'.$field->id][] = 'required';
            }
            if ($field->is_required && $field->type == FieldType::EMAIL) {
                $rules['field_'.$field->id][] = 'email';
            }
            if (! $field->is_required) {
                $rules['field_'.$field->id][] = 'sometimes';
            }
        }

        $validated = $request->validate($rules);

        $application = $form->createApplication($validated);
        $application->sendNotifications();

        return response()->json([
            'heading' => (new MultilingualService($form->options['thank-you.heading']))->toString(),
            'message' => (new MultilingualService($form->options['thank-you.text']))->toString(),
            'success' => true,
        ]);
    }
}
