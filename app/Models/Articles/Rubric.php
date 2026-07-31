<?php

namespace App\Models\Articles;

use App\Casts\Api\ImageCast;
use App\Casts\Api\MultilingualCast;
use App\Casts\Api\SeoCast;
use App\Models\Scopes\SiteScope;
use App\Services\Cache;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Rubric extends Model implements Responsable
{
    protected $table = 'articles_rubrics';

    protected $fillable = [
        'site_id',
        'image',
        'pre_heading',
        'title',
        'slug',
        'is_published',
        'seo',
        'details',
        'content',
        'position',
    ];

    protected $casts = [
        'image' => ImageCast::class,
        'title' => MultilingualCast::class,
        'content' => MultilingualCast::class,
        'pre_heading' => MultilingualCast::class,
        'details' => MultilingualCast::class,
        'seo' => SeoCast::class,
        'is_published' => 'boolean',
    ];

    public static function booted()
    {
        self::addGlobalScope(SiteScope::class);
    }

    public function url(): Attribute
    {
        return Attribute::get(fn () => locale_url($this->slug));
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(
            Article::class,
            'article_to_rubrics',
            'rubric_id',
            'article_id'
        )->without('tags', 'rubrics')->orderByDesc('articles.published_at');
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
            'title' => $this->title->toResponse($request),
            'pre_heading' => $this->pre_heading->toResponse($request),
            'details' => $this->details->toResponse($request),
            'slug' => $this->slug,
            'is_published' => $this->is_published,
            'seo' => $this->seo->toResponse($request),
            'content' => $this->content->toResponse($request),
        ];
    }

    public static function items(): Collection
    {
        return Cache::remember('all_rubrics', fn () => self::published()->get());
    }
}
