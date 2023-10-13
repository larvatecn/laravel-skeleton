<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_extras', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary()->comment('用户ID');
            $table->unsignedBigInteger('referrer_id')->nullable()->comment('推荐人UserID');
            $table->ipAddress('last_login_ip')->nullable()->comment('最后登录IP地址');
            $table->unsignedInteger('invite_count')->default(0)->nullable()->comment('邀请人数');
            $table->string('invite_code')->nullable()->comment('邀请码');
            $table->unsignedTinyInteger('username_change_count')->default(0)->nullable()->comment('用户名修改次数');
            $table->unsignedBigInteger('login_count')->nullable()->default(0)->comment('登录次数');
            $table->timestamp('first_active_at')->nullable()->comment('首次活动时间');
            $table->timestamp('last_active_at')->nullable()->comment('最后活动时间');
            $table->timestamp('last_login_at')->nullable()->comment('最后登录时间');
            $table->timestamp('phone_verified_at')->nullable()->comment('手机验证时间');
            $table->timestamp('email_verified_at')->nullable()->comment('邮件验证时间');
            $table->json('settings')->nullable()->comment('用户设置');

            $table->comment('用户扩展信息表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_extras');
    }
};
