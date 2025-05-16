<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasTranslations;

    protected $table = 'users';

    public $translatable = ['name'];

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    # Relations
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }
}
