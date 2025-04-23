<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'type', // 1 => invoice, 2 => payment, 3 => refund, 4 => coupon
        'teacher_id',
        'student_id',
        'invoice_id',
        'amount',
        'balance_after',
        'description',
        'payment_method', // 1 => cash, 2 => vodafone_cash, 3 => balance
        'date',
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

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
