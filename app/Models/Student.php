<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasTranslations, SoftDeletes;

    protected $table = 'students';

    public $translatable = ['name'];

    protected $fillable = [
        'username',
        'password',
        'name',
        'phone',
        'email',
        'gender',
        'birth_date',
        'grade_id',
        'parent_id',
        'balance',
        'is_active',
        'is_exempted',
        'fees_discount',
        'profile_pic',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    # Relationships
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function parent()
    {
        return $this->belongsTo(MyParent::class, 'parent_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'student_teacher');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'student_group');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    public function studentAccount()
    {
        return $this->hasMany(StudentAccount::class, 'student_id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'student_id');
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class, 'student_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'student_id');
    }

    public function studentResults()
    {
        return $this->hasMany(StudentResult::class, 'student_id');
    }

    public function StudentViolations()
    {
        return $this->hasMany(StudentViolation::class, 'student_id');
    }

    # Scopes
    public function scopeMale($query)
    {
        return $query->where('gender', 1);
    }

    public function scopeFemale($query)
    {
        return $query->where('gender', 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    public function scopeExempted($query)
    {
        return $query->where('is_exempted', 1);
    }
}
