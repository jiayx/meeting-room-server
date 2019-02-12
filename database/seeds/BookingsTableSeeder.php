<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        $dateRange = [
            [
                '08:30:00',
                '09:30:00',
            ],
            [
                '010:30:00',
                '12:30:00',
            ],
            [
                '13:30:00',
                '14:30:00',
            ],
            [
                '14:30:00',
                '15:30:00',
            ],
            [
                '16:30:00',
                '17:30:00',
            ],
        ];

        $users = [1, 2];

        $now = Carbon::now();

        foreach ($users as $userId) {
            foreach ($dateRange as $item) {
                list($startTime, $endTime) = $item;

                $data[] = [
                    'meeting_room_id' => 2,
                    'user_id' => $userId,
                    'subject' => '吃饭',
                    'date' => '2017-01-0'.$userId,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('bookings')->insert($data);
    }
}
