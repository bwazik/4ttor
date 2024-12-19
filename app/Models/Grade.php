<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Grade extends Model
{
    use HasTranslations;

    protected $table = 'grades';

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'is_active',
        'stage_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function stage(){
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_grade');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }
}
