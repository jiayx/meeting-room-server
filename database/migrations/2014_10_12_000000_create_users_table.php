<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('nickname')->default('');
            $table->string('email')->default('');
            $table->string('mobile', 20)->default('');
            $table->string('wanxin', 30)->default('')->index()->comment('万信号');
            $table->string('openid', 64)->index()->default('');
            $table->string('avatar')->default('')->comment('头像 url');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
