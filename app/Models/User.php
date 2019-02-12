<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Encryption\Encrypter;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'openid', 'name', 'email', 'nickname', 'avatar', 'password',
    ];

    protected $appends = [
        'encrypted_user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getEncryptedUserIdAttribute()
    {
        $base64 = app(Encrypter::class)->encryptString($this->id);

        return base64_url_safe_encode($base64);
    }
}
