<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TeacherSubscription extends Model
{
    protected $table = 'teacher_subscriptions';

    protected $fillable = [
        'teacher_id',
        'plan_id',
        'period',
        'start_date',
        'end_date',
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'subscription_id');
    }

    # Accessors
    public function getAmountAttribute()
    {
        if ($this->period == 1) {
            return $this->plan->monthly_price;
        } elseif ($this->period == 2) {
            return $this->plan->term_price ?? $this->plan->monthly_price;
        } elseif ($this->period == 3) {
            return $this->plan->year_price ?? $this->plan->monthly_price;
        } else {
            return $this->plan->monthly_price;
        }
    }
}
