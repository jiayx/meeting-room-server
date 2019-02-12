<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('会议室名字');
            $table->tinyInteger('available')->index()->comment('是否可用 1 可用 0 不可用');
            $table->integer('location_id')->comment('所在位置 id');
            $table->integer('floor')->comment('所在楼层');
            $table->integer('seating_count')->default(0)->comment('座位数');
            $table->integer('projector_count')->default(0)->comment('投影仪数');
            $table->integer('phone_count')->default(0)->comment('电话数');
            $table->timestamps();

            $table->index(['location_id', 'floor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_rooms');
    }
}
