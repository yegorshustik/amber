<?php

namespace App\Models;

use App\Casts\Api\MultilingualCast;
use App\Casts\Api\PageComposerCast;
use App\Casts\Api\SeoCast;
use App\Services\Localization;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model implements Responsable
{
    use NodeTrait;

    protected $table = 'pages';

    protected $fillable = [
        'site_id',
        'parent_id',
        'url',
        'title',
        'slug',
        'is_published',
        'seo',
        'content',
        'options',
        'position',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'title' => MultilingualCast::class,
        'content' => PageComposerCast::class,
        'seo' => SeoCast::class,
        'options' => 'array',
    ];

    public static function usesSoftDelete(): bool
    {
        return false;
    }

    public function scopeSite(Builder $builder, ?int $id = null)
    {
        $builder->where('site_id', $id ?? site()->id);
    }

    public function scopeFindUrl(Builder $builder, ?string $url = null)
    {
        $url ??= request()->path();
        $url = trim($url, '/');
        $url = (new Localization)->clearUrl($url);
        $url = $url ? ('index/'.$url) : 'index';

        $builder->where('url', trim($url, '/'));
    }

    public function scopePublished(Builder $builder)
    {
        $builder->where('is_published', true);
    }

    public function url(): Attribute
    {
        return new Attribute(
            get : fn (?string $url) => locale_url('/'.(trim(str_replace(['index/index', 'index'], '', $url), '/') ?: ''))
        );
    }

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'title' => $this->title->toResponse($request),
            'slug' => $this->slug,
            'is_published' => $this->is_published,
            'seo' => $this->seo->toResponse($request),
            'content' => $this->content->toResponse($request),
            'options' => $this->options,
        ];
    }
}
