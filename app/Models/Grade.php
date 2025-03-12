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

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function stage(){
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'grade_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_grade');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'grade_id');
    }

    public function fees()
    {
        return $this->hasMany(Fee::class, 'grade_id');
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
