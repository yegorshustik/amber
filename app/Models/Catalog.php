<?php

namespace App\Models;

use App\Casts\Api\ImageCast;
use App\Casts\Api\MultilingualCast;
use App\Casts\Api\SeoCast;
use App\Enums\Catalog\ItemType;
use App\Services\Api\MultilingualService;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Catalog extends Model implements Responsable
{
    protected $table = 'catalog';

    protected $fillable = [
        'id',
        'type',
        'title',
        'is_published',
        'is_visible',
        'slug',
        'country',
        'city',
        'short_details',
        'details',
        'age_range',
        'gender',
        'boarding',
        'curriculum',
        'size',
        'campus_style',
        'programs',
        'degrees',
        'acceptance',
        'established',
        'image',
        'pre_heading',
        'heading',
        'content',
        'faq',
        'seo',
        'position',
    ];

    protected $casts = [
        'type' => ItemType::class,
        'title' => MultilingualCast::class,
        'is_published' => 'boolean',
        'is_visible' => 'boolean',
        'country' => MultilingualCast::class,
        'city' => MultilingualCast::class,
        'short_details' => MultilingualCast::class,
        'details' => MultilingualCast::class,
        'age_range' => MultilingualCast::class,
        'gender' => MultilingualCast::class,
        'boarding' => MultilingualCast::class,
        'curriculum' => MultilingualCast::class,
        'size' => MultilingualCast::class,
        'campus_style' => MultilingualCast::class,
        'programs' => MultilingualCast::class,
        'degrees' => MultilingualCast::class,
        'acceptance' => MultilingualCast::class,
        'established' => MultilingualCast::class,
        'image' => ImageCast::class,
        'pre_heading' => MultilingualCast::class,
        'heading' => MultilingualCast::class,
        'content' => MultilingualCast::class,
        'faq' => 'array',
        'seo' => SeoCast::class,
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->position = self::max('position') + 1;
        });
    }

    public function scopePublished(Builder $builder, bool $published = true)
    {
        $builder->whereIsPublished($published)
            ->orderBy('position');
    }

    public function scopeVisible(Builder $builder, bool $visible = true)
    {
        $builder->whereIsPublished(true)
            ->whereIsVisible($visible)
            ->orderBy('position');
    }

    public function url(): Attribute
    {
        return new Attribute(
            get : fn () => locale_url('catalog/'.$this->slug)
        );
    }

    public function getFaq(): Collection
    {
        return collect($this->faq)->map(fn ($item) => [
            'question' => new MultilingualService($item['question']),
            'answer' => new MultilingualService($item['answer']),
        ]);
    }

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type->toResponse($request),
            'title' => $this->title->toResponse($request), // +
            'is_published' => $this->is_published, // +
            'is_visible' => $this->is_visible, // +
            'slug' => $this->slug, // +
            'country' => $this->country->toResponse($request),
            'city' => $this->city->toResponse($request),
            'short_details' => $this->short_details->toResponse($request),
            'details' => $this->details->toResponse($request),
            'age_range' => $this->age_range->toResponse($request),
            'gender' => $this->gender->toResponse($request),
            'boarding' => $this->boarding->toResponse($request),
            'curriculum' => $this->curriculum->toResponse($request),
            'size' => $this->size->toResponse($request),
            'campus_style' => $this->campus_style->toResponse($request),
            'acceptance' => $this->acceptance->toResponse($request),
            'programs' => $this->programs->toResponse($request),
            'degrees' => $this->degrees->toResponse($request),
            'established' => $this->established->toResponse($request),
            'image' => $this->image->toResponse($request), // +
            'pre_heading' => $this->pre_heading->toResponse($request),
            'heading' => $this->heading->toResponse($request),
            'content' => $this->content->toResponse($request),
            'faq' => $this->faq,
            'seo' => $this->seo->toResponse($request),
            'position' => $this->id,
        ];
    }
}
