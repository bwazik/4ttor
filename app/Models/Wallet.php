<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'balance',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    # Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
