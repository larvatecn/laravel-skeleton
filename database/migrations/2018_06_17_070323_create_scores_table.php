<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index()->comment('用户ID');
            $table->integer('score')->comment('本次交易的积分数');
            $table->unsignedInteger('current_score')->comment('该笔交易发生后，用户的积分数');
            $table->string('description')->comment('描述');
            $table->morphs('source');
            $table->string('type')->comment('交易类型');
            $table->ipAddress('client_ip')->nullable()->comment('客户端IP');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
