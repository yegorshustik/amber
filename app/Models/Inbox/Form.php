<?php

namespace App\Models\Inbox;

use App\Casts\Api\MultilingualCast;
use App\Http\Resources\Api\Inbox\FieldsCollection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Livewire\Wireable;

class Form extends Model implements Responsable, Wireable
{
    protected $table = 'inbox_forms';

    protected $fillable = [
        'title',
        'slug',
        'is_published',
        'recipients',
        'options',
        'position',
    ];

    protected $casts = [
        'title' => MultilingualCast::class,
        'is_published' => 'boolean',
        'options' => 'array',
    ];

    protected $with = [
        'fields',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'form_id')->orderByDesc('created_at');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class, 'form_id')->orderBy('position');
    }

    public function createApplication(array $validated, array $options = []): Application
    {
        $hash = $this->createHash($validated);

        $application = Application::updateOrCreate([
            'hash' => $hash,
        ], [
            'hash' => $hash,
            'form_id' => $this->id,
            'options' => $options,
        ]);

        DB::table('inbox_application_fields')
            ->where('form_id', $this->id)
            ->where('application_id', $application->id)
            ->delete();

        foreach ($this->fields as $field) {
            if (isset($validated['field_'.$field->id])) {
                ApplicationFields::create([
                    'form_id' => $this->id,
                    'application_id' => $application->id,
                    'field_id' => $field->id,
                    'content' => is_array($validated['field_'.$field->id]) ? implode(', ', $validated['field_'.$field->id]) : $validated['field_'.$field->id],
                ]);
            }
        }

        return $application;
    }

    public function createHash($validated): string
    {
        $hash = [];

        foreach ($validated as $key => $value) {
            if (str_starts_with($key, 'field')) {
                $hash[$key] = $value;
            }
        }

        return md5(json_encode($hash));
    }

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title->toResponse($request),
            'slug' => $this->slug,
            'is_published' => $this->is_published,
            'options' => $this->options,
            'fields' => FieldsCollection::make($this->fields)->toArray($request)['data'] ?? [],
            'recipients' => $this->recipients,
        ];
    }

    /*
     * Livewire stuff
     */
    public function toLivewire()
    {
        return [
            'id' => $this->id,
        ];
    }

    public static function fromLivewire($value)
    {
        return Form::with([
            'fields' => function (HasMany $builder) {
                $builder->whereIsPublished(true);
            },
        ])->whereIsPublished(true)->find($value['id']);
    }
}
