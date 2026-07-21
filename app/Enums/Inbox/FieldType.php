<?php

namespace App\Enums\Inbox;

use Illuminate\Contracts\Support\Responsable;

enum FieldType: string implements Responsable
{
    case TEXT = 'text';
    case EMAIL = 'email';
    case TEL = 'tel';
    case TEXTAREA = 'textarea';

    public function title()
    {
        return match ($this) {
            self::TEXT => __('cms.inbox.fields.type.text'),
            self::EMAIL => __('cms.inbox.fields.type.email'),
            self::TEL => __('cms.inbox.fields.type.tel'),
            self::TEXTAREA => __('cms.inbox.fields.type.textarea'),
        };
    }

    public function toResponse($request)
    {
        return [
            'value' => $this->value,
            'title' => $this->title(),
        ];
    }
}
