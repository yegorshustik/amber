<?php

namespace App\Enums\Catalog;

use Illuminate\Contracts\Support\Responsable;

enum ItemType: string implements Responsable
{
    case SCHOOL = 'school';
    case UNIVERSITY = 'university';

    public function title(bool $plural = false)
    {
        return match ($this) {
            self::SCHOOL => __($plural ? 'catalog.type.schools' : 'catalog.type.school'),
            self::UNIVERSITY => __($plural ? 'catalog.type.universies' : 'catalog.type.university'),
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
