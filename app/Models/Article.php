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

    public function articleContents()
    {
        return $this->hasMany(ArticleContent::class, 'article_id');
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

        public function scopeForTeachers($query)
    {
        return $query->whereIn('audience', [1, 5, 7]); // Teachers, Teachers & Assistants, All
    }

    public function scopeForStudents($query)
    {
        return $query->whereIn('audience', [2, 6, 7]); // Students, Students & Parents, All
    }

    public function scopeForAssistants($query)
    {
        return $query->whereIn('audience', [3, 5, 7]); // Assistants, Teachers & Assistants, All
    }

    public function scopeForParents($query)
    {
        return $query->whereIn('audience', [4, 6, 7]); // Parents, Students & Parents, All
    }

    public function scopeForTeachersAndAssistants($query)
    {
        return $query->whereIn('audience', [5, 7]); // Teachers & Assistants, All
    }

    public function scopeForStudentsAndParents($query)
    {
        return $query->whereIn('audience', [6, 7]); // Students & Parents, All
    }

    public function scopeForAll($query)
    {
        return $query->where('audience', 7); // All
    }
}
