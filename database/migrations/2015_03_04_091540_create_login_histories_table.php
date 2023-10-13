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
        Schema::create('login_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index()->comment('用户ID');
            $table->ipAddress('ip')->comment('登录IP');
            $table->unsignedInteger('port')->nullable()->comment('登录端口');
            $table->string('browser')->nullable()->default('Unknown');
            $table->string('user_agent', 1200)->nullable();
            $table->string('address')->nullable();
            $table->timestamp('login_at')->nullable();

            $table->comment('登录历史表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
