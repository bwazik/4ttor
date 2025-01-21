<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Fee extends Model
{
    use HasTranslations;

    protected $table = 'fees';

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'amount',
        'teacher_id',
        'grade_id',
        'created_at',
    ];

    protected $hidden = [
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
