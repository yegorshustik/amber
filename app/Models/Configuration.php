<?php

namespace App\Models;

use App\Models\Scopes\SiteScope;
use App\Services\Api\FileService;
use App\Services\Api\ImageService;
use App\Services\Api\MultilingualService;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model implements Responsable
{
    protected $table = 'configurations';

    protected $fillable = [
        'site_id',
        'slug',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(SiteScope::class);
    }

    public static function buildRaw(): array
    {
        $params = [];

        foreach (self::get() as $param) {
            $params[$param->slug] = $param->content;
        }

        return $params;
    }

    public static function mapped(): array
    {
        $params = [];

        foreach (self::get() as $param) {
            $params[$param->slug] = $param->mappedValue();
        }

        return $params;
    }

    public function mappedValue()
    {
        return match ($this->slug) {
            'seo.default-og',
            'branding.project-logo' => new ImageService($this->content ?? []),
            'contacts.vcf' => new FileService($this->content ?? []),
            'equipment.text', 'catalog.brands.text' => new MultilingualService($this->content ?? []),
            default => $this->content,
        };
    }

    public function toResponse($request)
    {
        $content = $this->mappedValue();

        return [
            'slug' => $this->slug,
            'content' => config('system.'.$this->slug),
            'content_raw' => $content instanceof Responsable ? $content->toResponse($request) : $content,
        ];
    }
}
