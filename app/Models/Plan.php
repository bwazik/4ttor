<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Plan extends Model
{
    use HasTranslations;

    protected $table = 'plans';

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'monthly_price',
        'term_price',
        'year_price',
        'student_limit',
        'parent_limit',
        'assistant_limit',
        'group_limit',
        'quiz_monthly_limit',
        'quiz_term_limit',
        'quiz_year_limit',
        'assignment_monthly_limit',
        'assignment_term_limit',
        'assignment_year_limit',
        'attendance_reports',
        'financial_reports',
        'performance_reports',
        'whatsapp_messages',
        'is_active',
    ];

    protected $hidden = [];


    # Relationships
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'plan_id');
    }

    public function teacherSubscriptions()
    {
        return $this->hasMany(TeacherSubscription::class, 'plan_id');
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
