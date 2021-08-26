<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->unsignedBigInteger('user_id')->nullable()->comment('用户ID');
            $table->string('token')->unique()->comment('Token');
            $table->string('os', 20)->comment('操作系统');
            $table->string('imei')->nullable()->comment('设备的国际移动设备身份码');
            $table->string('imsi')->nullable()->comment('设备的国际移动用户识别码');
            $table->string('model')->nullable()->comment('设备型号');
            $table->string('vendor')->nullable()->comment('设备供应商');
            $table->string('version')->nullable()->comment('APP版本');
            $table->timestamps();

            $table->unique(['token', 'os']);

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
        Schema::dropIfExists('devices');
    }
}
