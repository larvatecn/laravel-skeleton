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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary()->comment('用户ID');
            $table->unsignedTinyInteger('gender')->default(0)->comment('性别：0/1/2');
            $table->date('birthday')->nullable()->comment('生日');
            $table->unsignedInteger('company_id')->nullable()->comment('公司ID');
            $table->unsignedInteger('province_id')->nullable()->comment('省ID');
            $table->unsignedInteger('city_id')->nullable()->comment('市ID');
            $table->unsignedInteger('area_id')->nullable()->comment('区县ID');
            $table->string('website')->nullable()->comment('个人网站');
            $table->string('intro')->nullable()->comment('个人简介');
            $table->text('bio')->nullable()->comment('个性签名');

            $table->comment('用户资料表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
