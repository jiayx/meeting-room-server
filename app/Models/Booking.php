<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Encryption\Encrypter;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'meeting_room_id', 'user_id',
        'subject', 'attendance_count',
        'date', 'start_time', 'end_time',
        'deleted_at', 'created_at', 'updated_at',
    ];

    protected $appends = [
        'encrypted_user_id',
    ];

    protected $hidden = [
        // 'user_id',
    ];

    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEncryptedUserIdAttribute()
    {
        $base64 = app(Encrypter::class)->encryptString($this->user_id);

        return base64_url_safe_encode($base64);
    }
}
