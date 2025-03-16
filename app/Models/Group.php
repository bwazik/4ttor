<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Group extends Model
{
    use HasTranslations;

    protected $table = 'groups';

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'teacher_id',
        'grade_id',
        'day_1',
        'day_2',
        'time',
        'is_active',
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

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_group');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'group_id');
    }

    public function zooms()
    {
        return $this->hasMany(Zoom::class, 'group_id');
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

    # Mutators
    public function setTimeAttribute($value)
    {
        $this->attributes['time'] = Carbon::parse($value)->format('H:i');
    }

    # Accessors
    public function getTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
    public function getCreatedAtAttribute($value)
    {
        return isoFormat($value);
    }
    public function getUpdatedAtAttribute($value)
    {
        return isoFormat($value);
    }
}
