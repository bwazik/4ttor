<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubscription extends Model
{
    protected $table = 'teacher_subscriptions';

    protected $fillable = [
        'teacher_id',
        'plan_id',
        'start_date',
        'end_date',
        'amount',
        'status',
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

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
