<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSigningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('score')->nullable()->default(0)->comment('赠送的积分数');
            $table->ipAddress('client_ip')->nullable()->comment('客户端IP');
            $table->string('transaction_id')->nullable()->comment('流水ID');
            $table->timestamp('created_at')->nullable()->comment('签到时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signings');
    }
}
