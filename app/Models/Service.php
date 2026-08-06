<?php

namespace App\Models;

use App\Casts\Api\MultilingualCast;
use App\Casts\Api\PageComposerCast;
use App\Casts\Api\SeoCast;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Service extends Model implements Responsable
{
    protected $table = 'services';

    protected $fillable = [
        'title',
        'details',
        'is_published',
        'slug',
        'content',
        'seo',
        'position',
    ];

    protected $casts = [
        'title' => MultilingualCast::class,
        'details' => MultilingualCast::class,
        'is_published' => 'boolean',
        'seo' => SeoCast::class,
        'content' => PageComposerCast::class,
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
        $builder->orderBy('position');
    }

    public function url(): Attribute
    {
        return new Attribute(
            get : fn () => locale_url('services/'.$this->slug)
        );
    }

    public function rawUrl(): Attribute
    {
        return Attribute::get(fn () => 'services/'.$this->slug);
    }

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title->toResponse($request),
            'details' => $this->details->toResponse($request),
            'slug' => $this->slug,
            'is_published' => $this->is_published,
            'content' => $this->content->toResponse($request),
        ];
    }
}
