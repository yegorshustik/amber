<?php

namespace App\Models;

use App\Casts\Api\ImageCast;
use App\Casts\Api\ImagesCast;
use App\Casts\Api\MultilingualCast;
use App\Casts\Api\SeoCast;
use App\Models\Catalog\Category;
use App\Rules\Api\RequiredMultilingualRule;
use App\Services\Cache;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Review extends Model implements Responsable
{
    protected $table = 'reviews';

    protected $fillable = [
        'id',
        'published_at',
        'image',
        'name',
        'job',
        'is_published',
        'content',
        'position',
    ];

    protected $casts = [
        'image' => ImageCast::class,
        'job' => MultilingualCast::class,
        'name' => MultilingualCast::class,
        'is_published' => 'boolean',
        'content' => MultilingualCast::class,
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

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image->toResponse($request),
            'name' => $this->name->toResponse($request),
            'job' => $this->job->toResponse($request),
            'content' => $this->content->toResponse($request),
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
        ];
    }

    public static function items(int $limit = 3): Collection
    {
        return Cache::remember('all_reviews_'.$limit, fn () => self::query()
            ->published()
            ->get());
    }
}
