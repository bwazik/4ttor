<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleContent extends Model
{
    protected $table = 'article_contents';

    protected $fillable = [
        'article_id',
        'type', // 1 => text, 2 => image
        'content',
        'caption',
        'order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
