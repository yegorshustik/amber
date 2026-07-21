<?php

namespace App\Models\Inbox;

use App\Casts\Api\MultilingualCast;
use App\Enums\Inbox\FieldType;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;

class Field extends Model implements Responsable
{
    protected $table = 'inbox_form_fields';

    protected $fillable = [
        'form_id',
        'type',
        'title',
        'placeholder',
        'is_published',
        'is_required',
        'is_fullsize',
        'in_table',
        'position',
    ];

    protected $casts = [
        'type' => FieldType::class,
        'title' => MultilingualCast::class,
        'placeholder' => MultilingualCast::class,
        'is_published' => 'boolean',
        'is_required' => 'boolean',
        'is_fullsize' => 'boolean',
        'in_table' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->position = self::where('form_id', $model->form_id)->max('position') + 1;
        });
    }

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title->toResponse($request),
            'placeholder' => $this->placeholder->toResponse($request),
            'is_published' => $this->is_published,
            'is_required' => $this->is_required,
            'is_fullsize' => $this->is_fullsize,
            'in_table' => $this->in_table,
            'type' => $this->type->toResponse($request),
        ];
    }
}
