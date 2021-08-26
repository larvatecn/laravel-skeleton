<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('username', 50)->unique()->nullable()->comment('用户名');//用户名。
            $table->string('email')->unique()->nullable()->comment('邮箱');//邮箱
            $table->string('mobile', 11)->unique()->nullable()->comment('手机号');//手机号码
            $table->string('avatar_path', 191)->nullable();//头像地址
            $table->string('password')->comment('密码');
            $table->boolean('identified')->nullable()->default(false)->comment('是否经过实名认证');
            $table->unsignedTinyInteger('status')->nullable()->default(0)->comment('用户状态：0 正常 1 禁用 2 审核中 3 审核拒绝');
            $table->unsignedInteger('available_amount')->nullable()->default(0)->comment('用户余额');
            $table->unsignedInteger('score')->nullable()->default(0)->comment('用户积分');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
