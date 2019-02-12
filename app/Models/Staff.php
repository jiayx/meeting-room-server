<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'wanxin', 'name', 'gender', 'mobile', 'title', 'department', 'created_at', 'updated_at',
    ];
}
