<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'teacher_id',
        'assistant_id',
        'student_id',
        'action',
        'details',
    ];

    protected $hidden = [
        'created_at',
    ];


    # Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function assistant()
    {
        return $this->belongsTo(Assistant::class, 'assistant_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
