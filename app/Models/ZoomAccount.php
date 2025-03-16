<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;

class ZoomAccount extends Model
{
    protected $table = 'zoom_accounts';

    protected $fillable = [
        'teacher_id',
        'account_id',
        'client_id',
        'client_secret',
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

    # Mutators
    public function setAccountIdAttribute($value)
    {
        $this->attributes['account_id'] = Crypt::encryptString($value);
    }

    public function setClientIdAttribute($value)
    {
        $this->attributes['client_id'] = Crypt::encryptString($value);
    }

    public function setClientSecretAttribute($value)
    {
        $this->attributes['client_secret'] = Crypt::encryptString($value);
    }


    # Accessors
    public function getAccountIdAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function getClientIdAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function getClientSecretAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }
}
