<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\MeetingRoom;
use App\Models\Schedule;
use Illuminate\Http\Request;

class MeetingRoomController extends ApiController
{
    /**
     * 查找可用会议室
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date_format:Y-m-d',
            'location_id' => 'integer',
            'floor' => 'integer',
        ]);

        $params = $request->only(['date', 'location_id', 'floor']);

        $query = MeetingRoom::query();

        // 取指定地点的会议室
        if ($params['location_id']) {
            $query = $query->where('location_id', $params['location_id']);
        }

        // 取指定地点 指定楼层的会议室
        if ($params['location_id'] && $params['floor']) {
            $query = $query->where('floor', $params['floor']);
        }

        // 取指定日期的预订
        if ($params['date']) {
            $query = $query->with(['bookings' => function ($query) use ($params) {
                $query->whereDate('date', $params['date']);
            }]);
        }

        $meetingRooms = $query->get();

        $meetingRooms->transform(function ($item) use ($params) {
            $times = Schedule::all()->toArray();

            if ($params['date']) {
                foreach ($item->bookings as $booking) {
                    $startTime = $booking->start_time;
                    $endTime = $booking->end_time;

                    foreach ($times as $index => $time) {
                        if ($time['start'] >= $startTime && $time['end'] <= $endTime) {
                            $extra = [
                                'used' => true,
                                'booking_id' => $booking->id,
                                'user_id' => $booking->user_id,
                            ];

                            if ($time['start'] == $startTime) {
                                $extra['mark'] = 'begin';
                            } elseif ($time['end'] == $endTime) {
                                $extra['mark'] = 'end';
                            } else {
                                $extra['mark'] = '';
                            }

                            $times[$index] = $time + $extra;
                        }
                    }
                }
            }

            $item->times = $times;

            return $item;
        });

        return $this->response($meetingRooms);
    }

    // 获取预订时间段
    public function schedules()
    {
        $schedules = Schedule::all();

        return $this->response($schedules);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'available' => 'integer|in:0,1',
            'location_id' => 'required|integer',
            'floor' => 'required|integer',
        ]);

        $params = $request->only(['name', 'available', 'location_id', 'floor']);

        // 默认可用
        if ($params['available'] === null) {
            $params['available'] = 1;
        }

        $meetingRoom = MeetingRoom::create($params);

        return $this->response($meetingRoom);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeetingRoom  $meetingRoom
     * @return \Illuminate\Http\Response
     */
    public function show(MeetingRoom $meetingRoom)
    {
        return $this->response($meetingRoom);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MeetingRoom  $meetingRoom
     * @return \Illuminate\Http\Response
     */
    public function edit(MeetingRoom $meetingRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeetingRoom  $meetingRoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeetingRoom $meetingRoom)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'available' => 'integer|in:0,1',
            'location' => 'string|max:255',
            'floor' => 'string|max:30',
        ]);

        $params = array_except($request->all(), ['created_at', 'updated_at']);

        $meetingRoom->fill($params);
        $meetingRoom->save();

        return $this->response($meetingRoom);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeetingRoom  $meetingRoom
     * @return \Illuminate\Http\Response
     */
    public function destroy(MeetingRoom $meetingRoom)
    {
        $meetingRoom->delete();

        return $this->response();
    }
}
