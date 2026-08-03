<?php

namespace App\Models\Filemanager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class Directory extends Model
{
    use NodeTrait;

    protected $table = 'filemanager_directories';

    protected $fillable = [
        'parent_id',
        'title',
        'position',
    ];

    public static function usesSoftDelete(): bool
    {
        return false;
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'parent_id');
    }
}
