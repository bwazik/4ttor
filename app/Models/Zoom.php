<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Zoom extends Model
{
    use HasTranslations;

    protected $table = 'zooms';

    public $translatable = ['topic'];

    protected $fillable = [
        'teacher_id',
        'grade_id',
        'group_id',
        'meeting_id',
        'topic',
        'duration',
        'password',
        'start_time',
        'start_url',
        'join_url',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
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

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
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
