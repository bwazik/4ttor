<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Invoice extends Model
{
    use HasTranslations;

    protected $table = 'invoices';

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'date',
        'fee_id',
        'plan_id',
        'teacher_id',
        'student_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
