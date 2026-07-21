<?php

namespace App\Models\Filemanager;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $table = 'filemanager_files';

    protected $fillable = [
        'parent_id',
        'hash',
        'name',
        'file_name',
        'mime',
        'size',
        'path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function (self $model) {
            if (Storage::exists($model->path)) {
                Storage::delete($model->path);
            }
        });
    }

    public function scopeSearch(Builder $builder, string $query)
    {
        $builder->where('name', 'LIKE', "%{$query}%");
    }

    public function directory(): BelongsTo
    {
        return $this->belongsTo(Directory::class, 'parent_id');
    }
}
