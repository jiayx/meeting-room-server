<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Maatwebsite\Excel\Facades\Excel;

class BookingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::all();

        return view('admin.bookings', [
            'title' => '预订列表',
            'bookings' => $bookings,
        ]);
    }

    /**
     * 导出预订记录
     */
    public function export(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'start' => 'required|date_format:Y-m-d',
                'end' => 'required|date_format:Y-m-d',
            ]);

            $start = $request->input('start');
            $end = $request->input('end');

            list($startYear, $startMonth, $startDay) = explode('-', $start);
            list($endYear, $endMonth, $endDay) = explode('-', $end);

            $startDate = Carbon::create($startYear, $startMonth, $startDay);
            $endDate = Carbon::create($endYear, $endMonth, $endDay);

            $diff = $endDate->diff($startDate);

            if ($diff->days > 30) {
                return $this->error('400', '查询的时间间隔不能超过 30天');
            }

            $bookings = Booking::query()
                ->with('meetingRoom')
                ->with('user')
                ->whereDate('date', '>=', $start)
                ->whereDate('date', '<=', $end)
                ->get();

            $excel = Excel::create("{$start}-{$end}", function ($excel) use ($startDate, $endDate, $bookings) {
                $excel->setCreator('admin')->setCompany('ffan');
                $excel->setDescription("bookings from {$startDate->toDateString()} to {$endDate->toDateString()}");

                $bookingsDate = $bookings->groupBy('date');

                while($startDate->toDateString() <= $endDate->toDateString()) {
                    $excel->sheet($startDate->toDateString(), function($sheet) use ($startDate, $bookingsDate) {

                        $data = $bookingsDate->get($startDate->toDateString());
                        if ($data) {
                            $data = $data->toArray();
                            foreach ($data as $index => $datum) {
                                if ($index == 0) {
                                    $sheet->appendRow([
                                        'ID', '会议室', '会议主题', '日期', '开始时间', '结束时间', '预订时间', '万信号', '姓名'
                                    ]);
                                }

                                $sheet->appendRow([
                                    $datum['id'], $datum['meeting_room']['name'], $datum['subject'], $datum['date'], $datum['start_time'],
                                    $datum['end_time'], $datum['created_at'], $datum['user']['wanxin'], $datum['user']['name'],
                                ]);
                            }
                        }
                    });

                    $startDate->addDay();
                }
            })->export('xlsx');

            return response()->download($excel);

        } else {
            return view('admin.export');
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
