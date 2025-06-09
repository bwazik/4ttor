<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations;

    protected $table = 'faqs';

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'category_id',
        'audience',
        'question',
        'answer',
        'is_active',
        'order',
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
}
