<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Lesson extends Model
{
    use HasTranslations;

    protected $table = 'lessons';

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
        'uuid',
        'title',
        'group_id',
        'date',
        'time',
        'status', // 1 => Scheduled, 2 => Completed, 3 => Canceled
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    # Relationships
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'lesson_id');
    }

    # Scopes
    public function scopeUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    public function scopeUuids($query, $uuids)
    {
        return $query->whereIn('uuid', $uuids);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 1);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 2);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 3);
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
}
