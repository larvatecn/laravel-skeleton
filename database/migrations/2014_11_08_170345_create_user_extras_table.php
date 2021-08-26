<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_extras', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->unsignedBigInteger('referrer_id')->nullable()->comment('推荐人ID');
            $table->string('invite_code', 64)->comment('邀请码');
            $table->ipAddress('login_ip')->nullable()->comment('最后登录IP');
            $table->timestamp('login_at')->nullable()->comment('最后登录时间');
            $table->unsignedInteger('login_num')->default(0)->nullable()->comment('登录次数');
            $table->timestamp('first_sign_in_at')->nullable()->comment('开始签到时间');
            $table->unsignedInteger('invite_count')->default(0)->nullable()->comment('邀请人数');
            $table->unsignedInteger('views')->default(0)->nullable()->comment('访客数');
            $table->unsignedInteger('collections')->default(0)->nullable()->comment('收藏数');
            $table->unsignedInteger('articles')->default(0)->nullable()->comment('文章数');
            $table->unique(['user_id', 'invite_code']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_extras');
    }
}
