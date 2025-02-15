<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAccount extends Model
{
    protected $table = 'student_accounts';

    protected $fillable = [
        'type',
        'student_id',
        'invoice_id',
        'receipt_id',
        'refund_id',
        'debit',
        'credit',
        'created_at',
        'updated_at',
    ];

    # Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
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
