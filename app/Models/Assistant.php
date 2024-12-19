<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Assistant extends Authenticatable
{
    use HasTranslations, SoftDeletes;

    protected $table = 'assistants';

    public $translatable = ['name'];

    protected $fillable = [
        'username',
        'password',
        'name',
        'phone',
        'email',
        'teacher_id',
        'is_active',
        'profile_pic',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    # Relations
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
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
