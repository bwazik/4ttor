<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Quiz extends Model
{
    use HasTranslations;

    protected $table = 'quizzes';

    public $translatable = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'uuid',
        'teacher_id',
        'grade_id',
        'name',
        'duration',
        'start_time',
        'end_time',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'quiz_id');
    }

    public function studentResults()
    {
        return $this->hasMany(StudentResult::class, 'quiz_id');
    }

    public function StudentViolations()
    {
        return $this->hasMany(StudentViolation::class, 'quiz_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'quiz_group');
    }

    # Scopes
    public function scopeUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }
}
