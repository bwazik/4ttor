<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use HasTranslations, SoftDeletes;

    protected $table = 'teachers';

    public $translatable = ['name'];

    protected $fillable = [
        'username',
        'password',
        'name',
        'phone',
        'email',
        'subject_id',
        'plan_id',
        'is_active',
        'average_rating',
        'balance',
        'profile_pic',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'average_rating' => 'float',
        'balance' => 'float',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    # Relations
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'teacher_grade');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_teacher');
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
