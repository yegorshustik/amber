<?php

namespace App\Models\Inbox;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;

class ApplicationFields extends Model implements Responsable
{
    protected $table = 'inbox_application_fields';

    public $timestamps = false;

    protected $fillable = [
        'form_id',
        'application_id',
        'field_id',
        'content',
    ];

    protected $with = [
    ];

    public function toResponse($request)
    {
        return [

        ];
    }
}
