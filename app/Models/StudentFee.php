<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $table = 'student_fees';

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
        'uuid' ,
        'student_id',
        'fee_id',
        'discount',
        'is_exempted',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    # Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_fee_id');
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

    public function scopeExempted($query)
    {
        return $query->where('is_exempted', true);
    }

    public function scopeNotExempted($query)
    {
        return $query->where('is_exempted', false);
    }

    # Accessors
    public function getAmountAttribute()
    {
        if ($this->is_exempted) {
            return 0;
        }

        $feeAmount = $this->fee ? $this->fee->amount : 0;
        $finalAmount = $feeAmount * (1 - ($this->discount / 100));

        return number_format($finalAmount, 2);
    }
}
