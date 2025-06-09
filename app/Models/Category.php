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
}
