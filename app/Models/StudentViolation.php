<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentViolation extends Model
{
    protected $table = 'student_violations';

    protected $fillable = [
        'student_id',
        'quiz_id',
        'violation_type',
        'detected_at',
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
}
