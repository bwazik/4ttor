<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasTranslations;

    protected $table = 'articles';

    public $translatable = ['title', 'description'];

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'audience',
        'description',
        'is_active',
        'is_pinned',
        'published_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    # Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', 1);
    }
}
