<?php

namespace App\Models\Articles;

use App\Casts\Api\MultilingualCast;
use App\Models\Scopes\SiteScope;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model implements Responsable
{
    protected $table = 'articles_tags';

    protected $fillable = [
        'site_id',
        'title',
        'slug',
    ];

    protected $casts = [
        'title' => MultilingualCast::class,
    ];

    public static function booted()
    {
        self::addGlobalScope(SiteScope::class);
    }

    public function toResponse($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title->toResponse($request),
            'slug' => $this->slug,
        ];
    }
}
