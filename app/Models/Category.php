<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    protected $table = 'categories';

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'category_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
