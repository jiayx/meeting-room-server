<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '插入必要数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 时间
        $times = [];
        for ($i = 8; $i < 17; $i++) {
            $startHour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $endHour = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            $times[] = [
                'start' => $startHour.':30:00',
                'end' => $endHour.':30:00',
            ];
        }

        // DB::table('schedules')->truncate();
        DB::table('schedules')->insert($times);

        // 地址
        $now = Carbon::today();

        $data = [
            'id' => 1,
            'name' => '东方万国企业中心 B1 座',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        // DB::table('locations')->truncate();
        DB::table('locations')->insert($data);


        $data = [
            [
                'name' => '7楼1会',
                'location_id' => 1,
                'floor' => 7,
            ],
            [
                'name' => '7楼2会',
                'location_id' => 1,
                'floor' => 7,
            ],
            [
                'name' => '6楼1会',
                'location_id' => 1,
                'floor' => 6,
            ],
            [
                'name' => '6楼2会',
                'location_id' => 1,
                'floor' => 6,
            ],
            [
                'name' => '5楼2会',
                'location_id' => 1,
                'floor' => 5,
            ],
            /*[
                'name' => '5楼1会',
                'location_id' => 1,
                'floor' => 5,
            ],*/
        ];

        foreach ($data as $index => $item) {
            $data[$index] = array_merge($item, [
                'available' => 1,
                'seating_count' => 12,
                'projector_count' => 1,
                'phone_count' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // DB::table('meeting_rooms')->truncate();
        DB::table('meeting_rooms')->insert($data);
    }
}
