<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('country_code')->nullable()->comment('国家');
            $table->unsignedSmallInteger('gender')->nullable()->default(0)->comment('用户性别：0 未知 1 男性 2 女性');
            $table->unsignedInteger('province_id')->nullable()->comment('省');
            $table->unsignedInteger('city_id')->nullable()->comment('市');
            $table->unsignedInteger('district_id')->nullable()->comment('区');
            $table->string('address')->nullable()->comment('联系地址');
            $table->string('website')->nullable()->comment('个人网站');
            $table->string('introduction')->nullable()->comment('个人简介');
            $table->text('bio')->nullable()->comment('个性签名');

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
        Schema::dropIfExists('user_profiles');
    }
}
