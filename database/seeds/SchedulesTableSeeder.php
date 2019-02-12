<?php

use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $times = [];
        for ($i = 8; $i < 17; $i++) {
            $startHour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $endHour = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            $times[] = [
                'start' => $startHour.':30:00',
                'end' => $endHour.':30:00',
            ];
        }

        DB::table('schedules')->insert($times);
    }
}
