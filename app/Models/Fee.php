<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Fee extends Model
{
    use HasTranslations;

    protected $table = 'fees';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public $translatable = ['name'];

    protected $fillable = [
        'uuid',
        'name',
        'amount',
        'teacher_id',
        'grade_id',
        'frequency',
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

    # Scopes
    public function scopeUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    public function scopeUuids($query, $uuids)
    {
        return $query->whereIn('uuid', $uuids);
    }
}
