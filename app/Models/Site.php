<?php

namespace App\Models;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class Site extends Model implements Responsable
{
    protected $table = 'sites';

    protected $fillable = [
        'title',
        'is_published',
        'slug',
        'domain',
        'domain_alternative',
        'position',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->position = self::max('position') + 1;
        });
    }

    public function scopePublished(Builder $builder, bool $state = true): void
    {
        $builder->where('is_published', $state);
    }

    public function scopeFindByDomain(Builder $builder, string $domain): void
    {
        $builder->where(fn (Builder $builder) => $builder->where('domain', $domain)->orWhere('domain_alternative', $domain));
    }

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'is_published' => $this->is_published,
            'domain' => $this->domain,
            'domain_alternative' => $this->domain_alternative,
        ];
    }

    public function view(string $view, array $params = []): View
    {
        return view($this->slug.'.'.$view, $params);
    }
}
