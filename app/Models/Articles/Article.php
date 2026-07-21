<?php

namespace App\Models\Articles;

use App\Casts\Api\ImageCast;
use App\Casts\Api\MultilingualCast;
use App\Casts\Api\PageComposerCast;
use App\Casts\Api\SeoCast;
use App\Http\Resources\Api\Articles\RubricsCollection;
use App\Http\Resources\Api\Articles\TagsCollection;
use App\Models\Scopes\SiteScope;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model implements Responsable
{
    protected $table = 'articles';

    protected $fillable = [
        'site_id',
        'image',
        'title',
        'slug',
        'is_published',
        'published_at',
        'seo',
        'announcement',
        'content',
        'position',
    ];

    protected $with = ['tags', 'rubrics'];

    protected $casts = [
        'image' => ImageCast::class,
        'title' => MultilingualCast::class,
        'announcement' => MultilingualCast::class,
        'content' => PageComposerCast::class,
        'seo' => SeoCast::class,
        'published_at' => 'datetime:Y-m-d H:i:s',
        'is_published' => 'boolean',
    ];

    public static function booted()
    {
        self::addGlobalScope(SiteScope::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_to_tags');
    }

    public function rubrics(): BelongsToMany
    {
        return $this->belongsToMany(Rubric::class, 'article_to_rubrics')->orderBy('position');
    }

    public function url(): Attribute
    {
        return Attribute::get(fn () => locale_url('article/'.$this->slug));
    }

    public function scopePublished(Builder $builder)
    {
        $builder->where('is_published', true);
        $builder->where('published_at', '<=', \DB::raw('NOW()'));
        $builder->orderByDesc('published_at');
    }

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image->toResponse($request),
            'title' => $this->title->toResponse($request),
            'slug' => $this->slug,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'tags' => TagsCollection::make($this->tags)->toArray($request)['data'] ?? [],
            'rubrics' => RubricsCollection::make($this->rubrics)->toArray($request)['data'] ?? [],
            'seo' => $this->seo->toResponse($request),
            'announcement' => $this->announcement->toResponse($request),
            'content' => $this->content->toResponse($request),
        ];
    }
}
