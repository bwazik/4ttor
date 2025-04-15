<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Assignment extends Model
{
    use HasTranslations;

    protected $table = 'assignments';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public $translatable = ['title'];

    protected $fillable = [
        'teacher_id',
        'grade_id',
        'group_id',
        'title',
        'description',
        'deadline',
        'max_scores',
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

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id');
    }

    public function assignmentFiles()
    {
        return $this->hasMany(AssignmentFile::class, 'assignment_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'assignment_group');
    }

    # Scopes
    public function scopeUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }
}
