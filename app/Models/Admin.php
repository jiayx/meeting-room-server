<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
        'name', 'username', 'email', 'avatar', 'password', 'created_at', 'updated_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
