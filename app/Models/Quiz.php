<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Quiz extends Model
{
    use HasTranslations;

    protected $table = 'quizzes';

    public $translatable = ['name'];

    protected $fillable = [
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
}
