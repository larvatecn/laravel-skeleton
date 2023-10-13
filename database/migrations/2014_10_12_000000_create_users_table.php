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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('用户ID');
            $table->string('username')->unique()->nullable()->comment('用户名');
            $table->string('email')->unique()->nullable()->comment('邮箱');
            $table->string('phone', 11)->unique()->nullable()->comment('手机号');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('avatar')->nullable()->comment('头像');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0、active，1、frozen');
        
            $table->string('password')->nullable()->comment('密码');
            $table->rememberToken()->comment('记住我token');
            $table->timestamps();
            $table->softDeletes()->comment('删除时间');

            $table->comment('用户表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
