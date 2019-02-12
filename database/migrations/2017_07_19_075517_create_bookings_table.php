<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meeting_room_id')->comment('被预订会议室的 id');
            $table->integer('user_id')->comment('预订会议室的用户的 id');
            $table->string('subject')->default('')->comment('会议主题');
            $table->string('attendance_count')->default(0)->comment('参会人数');
            $table->date('date')->nullable()->comment('预订日期');
            $table->time('start_time')->nullable()->comment('开始时间');
            $table->time('end_time')->nullable()->comment('结束时间');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
