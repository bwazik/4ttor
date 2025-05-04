<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FinanceLog extends Model
{
    use HasTranslations;

    protected $table = 'audit_logs';

    public $translatable = ['details', 'details'];

    public $timestamps = false;

    protected $fillable = [
        'teacher_id',
        'assistant_id',
        'student_id',
        'action',
        'details',
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
