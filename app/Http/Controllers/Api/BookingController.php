<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Booking;
use App\Models\SuperUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends ApiController
{
    /**
     * 预订列表
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'offset' => 'integer',
            'limit' => 'integer',
        ]);

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $bookings = Booking::query()
            ->with('meetingRoom')
            ->where('user_id', current_user_id())
            ->offset($offset)
            ->limit($limit)
            ->orderBy('id', 'DESC')
            ->get();

        return $this->response($bookings);
    }
    /**
     * 预订会议室
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|string|max:255',
            'attendance_count' => 'required|integer',
            'meeting_room_id' => 'required|integer',
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:30:00',
            'end_time' => 'required|date_format:H:30:00',
        ]);

        $params = $request->only(['subject', 'attendance_count', 'meeting_room_id', 'date', 'start_time', 'end_time']);


        if ($params['end_time'] <= $params['start_time']) {
            return $this->error('400', '结束时间不能小于等于开始时间');
        }

        $today = Carbon::today();

        // 今天之前的不准订
        if ($params['date'] < $today->toDateString()) {
            return $this->error('400', '不能预订今天之前的会议室');
        }

        $user = current_user();
        if (!$user->wanxin) {
            return $this->error('400', '请先绑定万信号');
        }

        $superUsers = SuperUser::all()->pluck('wanxin');

        // 为 false 则不是 superUser 要被限制的
        if ($superUsers->search($user->wanxin) === false) {
            $theDayAfterTomorrow = $today->addDay(2)->toDateString();

            if ($params['date'] > $theDayAfterTomorrow) {
                return $this->error('400', '最早提前两天预订');
            }

            // 检测当前用户在指定日期订了多久 不能超过 2 小时
            // 先查之前今天订了多久
            $userBookings = Booking::query()
                ->whereDate('date', $params['date'])
                ->where('user_id', current_user_id())
                ->get();
            $startTime = '99:99:99';
            $endTime = '00:00:00';
            foreach ($userBookings as $userBooking) {
                if ($startTime > $userBooking->start_time) {
                    $startTime = $userBooking->start_time;
                }

                if ($endTime < $userBooking->end_time) {
                    $endTime = $userBooking->end_time;
                }
            }

            $startTime = intval(substr($startTime, 0, 2));
            $endTime = intval(substr($endTime, 0, 2));

            // 再算这次要订多久
            $thisTime = intval(substr($params['end_time'], 0, 2)) - intval(substr($params['start_time'], 0, 2));

            if (($endTime - $startTime + $thisTime) > 2) {
                return $this->error('400', '每人每天最多预订两个小时会议室');
            }
        }

        // 获取到这一天这个会议室的全部预订
        $bookings = Booking::query()
            ->where('meeting_room_id', $params['meeting_room_id'])
            ->whereDate('date', $params['date'])->get();

        $start = $params['start_time'];
        $end = $params['end_time'];

        $filtered = $bookings->filter(function ($booking) use ($start, $end) {
            // 互不交叉
            if ($end <= $booking->start_time || $start >= $booking->end_time) {
                return false;
            }

            return true;
        });

        // 时间上有交叉
        if ($filtered->count() > 0) {
            return $this->response(null, '400', '您选择的时间已经被预定啦！');
        }

        $params['user_id'] = current_user_id();

        $booking = Booking::create($params);

        return $this->response($booking);
    }

    public function show($id)
    {
        $booking = Booking::with('meetingRoom')->find($id);

        return $this->response($booking);
    }

    /**
     * 取消预订
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if ($booking && $booking->user_id === current_user_id()) {
            // TODO 时间判断
            $booking->delete();
        }

        return $this->response();
    }
}
