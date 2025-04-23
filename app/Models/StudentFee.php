<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $table = 'student_fees';

    protected $fillable = [
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

    # Scopes
    public function exempted($query)
    {
        return $query->where('is_exempted', true);
    }
}
