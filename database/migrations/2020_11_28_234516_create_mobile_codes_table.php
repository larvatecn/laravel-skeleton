<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_codes', function (Blueprint $table) {
            $table->id()->comment('验证码ID');
            $table->string('scene', 20)->nullable()->default('default')->comment('场景');
            $table->string('mobile', 20)->index()->comment('手机号');
            $table->string('code', 10)->comment('验证码');
            $table->tinyInteger('state')->default(0)->comment('验证状态');
            $table->ipAddress('ip')->default('')->comment('ip');
            $table->timestamp('send_at')->nullable()->comment('发送时间');
            $table->timestamp('usage_at')->nullable()->comment('使用时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_codes');
    }
}
