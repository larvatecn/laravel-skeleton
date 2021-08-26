<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('region', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级');
            $table->tinyInteger('level')->comment('等级');
            $table->string('name', 50)->index()->comment('名称');
            $table->string('initial', 50)->index()->comment('首字母');
            $table->string('pinyin')->index()->comment('拼音');
            $table->string('city_code', 10)->comment('城市编码');
            $table->string('ad_code', 10)->comment('区域编码');
            $table->string('lng_lat', 30)->comment('中心经纬度');
            $table->smallInteger('order')->default(0)->nullable()->comment('排序');
            $table->index(['name', 'initial', 'pinyin']);

            $table->index(['parent_id', 'order', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('region');
    }
}
