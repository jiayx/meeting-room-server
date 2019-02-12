<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wanxin', 64)->index()->comment('万信号');
            $table->string('name', 64)->comment('姓名');
            $table->string('gender', 10)->comment('性别 female/male');
            $table->string('mobile', 30)->index()->comment('手机号');
            $table->string('title', 64)->comment('职务');
            $table->string('department')->comment('部门');

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
        Schema::dropIfExists('staff');
    }
}
