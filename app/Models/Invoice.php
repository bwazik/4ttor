<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'invoices';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'uuid',
        'type', // 1 => subscription, 2 => fee
        'teacher_id',
        'student_id',
        'student_fee_id',
        'fee_id',
        'subscription_id',
        'amount',
        'date',
        'due_date',
        'status', // 1 => pending, 2 => paid, 3 => overdue, 4 => canceled
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    # Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class, 'student_fee_id');
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }

    public function subscription()
    {
        return $this->belongsTo(TeacherSubscription::class, 'subscription_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'invoice_id');
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

    public function scopeFee($query)
    {
        return $query->where('type', 2);
    }

    public function scopeSubscription($query)
    {
        return $query->where('type', 1);
    }

    public function scopePending($query)
    {
        return $query->where('status', 1);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 2);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 3);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 4);
    }
}
