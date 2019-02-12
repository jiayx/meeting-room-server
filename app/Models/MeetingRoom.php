<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MeetingRoom
 * @package App\Models
 * @method static MeetingRoom withSevenDayBookings()
 * @method static MeetingRoom withBookings()
 */
class MeetingRoom extends Model
{
    protected $fillable = ['name', 'available', 'location_id', 'floor', 'created_at', 'updated_at'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // 获取会议室下的预订记录
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // 未过期的的预约信息
    public function availableBookings()
    {
        return $this->bookings()->where('start_time', '>', Carbon::now());
    }

    // 过期的预约信息
    public function expireBookings()
    {
        return $this->bookings()->where('end_time', '<', Carbon::now());
    }

    // 七天内的预约信息
    public function sevenDayBookings()
    {
        return $this->bookings()
            ->where('start_time', '>', Carbon::today())
            ->where('start_time', '<', Carbon::today()->addDay(7));
    }


}
