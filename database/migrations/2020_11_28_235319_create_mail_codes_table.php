<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_codes', function (Blueprint $table) {
            $table->id()->comment('验证码ID');
            $table->string('email')->default('')->comment('邮箱地址');
            $table->string('code', 10)->default('')->comment('验证码');
            $table->tinyInteger('state')->default(0)->comment('验证状态');
            $table->ipAddress('ip')->default('')->comment('ip');
            $table->timestamp('send_at')->nullable();
            $table->timestamp('usage_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_codes');
    }
}
