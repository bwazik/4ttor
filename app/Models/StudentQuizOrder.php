<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuizOrder extends Model
{
    protected $table = 'student_quiz_order';

    protected $fillable = [
        'student_id',
        'quiz_id',
        'question_id',
        'display_order', // Order shown to student (1, 2, 3, ...)
        'answer_order', // Array of answer_id order for this question
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
