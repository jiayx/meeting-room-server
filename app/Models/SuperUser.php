<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperUser extends Model
{
    protected $fillable = [
        'wanxin', 'created_at', 'updated_at'
    ];
}
