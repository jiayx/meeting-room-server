<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MeetingRoomController extends ApiController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date_format:Y-m-d',
            'location_id' => 'integer',
            'floor' => 'integer',
        ]);

        $params = $request->only(['date', 'location_id', 'floor']);

        $meetingRoomQuery = MeetingRoom::query();
        if ($params['location_id']) {
            $meetingRoomQuery = $meetingRoomQuery->where('location_id', $params['location_id']);
        }
        $meetingRooms = $meetingRoomQuery->get();

        $times = Schedule::all();

        $bookings = Booking::query()
            ->with('user')
            ->whereIn('meeting_room_id', $meetingRooms->pluck('id'))
            ->whereDate('date', $params['date'])
            ->get();

        $times->transform(function ($time) use ($bookings, $meetingRooms) {
            $temp = [];

            // 为保证与会议室查询结果顺序一致
            foreach ($meetingRooms as $meetingRoom) {
                $temp[$meetingRoom->id] = [];
            }

            foreach ($bookings as $booking) {
                $startTime = $booking->start_time;
                $endTime = $booking->end_time;
                if ($time['start'] >= $startTime && $time['end'] <= $endTime) {
                    // 查找到这个时间段的预订
                    $temp[$booking->meeting_room_id] = $booking;
                }
            }

            // 排序跟 meeting_rooms 的排序一致
            $time->bookings = array_values($temp);

            return $time;
        });

        $data = [
            'meeting_rooms' => $meetingRooms,
            'times' => $times,
        ];

        return $this->response($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'available' => 'integer|in:0,1',
            'location' => 'required|string|max:255',
            'floor' => 'required|string|max:30',
        ]);

        $params = $request->only(['name', 'available', 'location', 'floor']);

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
