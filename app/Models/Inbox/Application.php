<?php

namespace App\Models\Inbox;

use App\Mail\Inbox;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

class Application extends Model implements Responsable
{
    protected $table = 'inbox_applications';

    protected $fillable = [
        'form_id',
        'hash',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    protected $with = [
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function entities(): HasMany
    {
        return $this->hasMany(ApplicationFields::class);
    }

    public function viewUrl(): Attribute
    {
        return Attribute::get(fn () => url('cms/inbox/form/'.$this->form->id.'#'.$this->id));
    }

    public function sendNotifications(): void
    {
        foreach (explode("\n", $this->form->recipients) as $recipient) {
            if (trim($recipient)) {
                Mail::to(trim($recipient))->send(new Inbox($this));
            }
        }
    }

    public function html(): string
    {
        $html = '<ul>';

        foreach ($this->form->fields as $field) {
            $entity = $this->entities->where('field_id', $field->id)->first();
            $option = null;

            if ($entity || $option) {
                $html .= '<li>';
                $html .= $field->title.': '.($option ? $option->title : $entity?->content);
                $html .= '</li>';
            }

        }
        $html .= '</ul>';

        return $html;
    }

    public function toResponse($request)
    {
        $result = $this->toArray();
        $result['created_at'] = $this->created_at->format('Y-m-d H:i:s');

        return $result;
    }
}
