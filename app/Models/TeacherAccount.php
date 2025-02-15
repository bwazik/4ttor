<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAccount extends Model
{
    protected $table = 'teacher_accounts';

    protected $fillable = [
        'type',
        'teacher_id',
        'invoice_id',
        'receipt_id',
        'refund_id',
        'debit',
        'credit',
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function refund()
    {
        return $this->belongsTo(Refund::class, 'refund_id');
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
