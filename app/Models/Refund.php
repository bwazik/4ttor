<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;

    protected $table = 'refunds';

    protected $fillable = [
        'date',
        'teacher_id',
        'student_id',
        'credit',
        'description',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    # Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function studentAccount()
    {
        return $this->hasMany(StudentAccount::class, 'refund_id');
    }

    public function teacherAccount()
    {
        return $this->hasMany(TeacherAccount::class, 'refund_id');
    }

    # Accessors
    public function getCreatedAtAttribute($value)
    {
        return isoFormat($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return isoFormat($value);
    }
}
