<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendshipLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friendship_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->index()->comment('类别');
            $table->string('title');
            $table->string('url');
            $table->string('logo_path')->nullable();
            $table->string('description')->nullable();
            $table->string('remark', 30)->nullable()->comment('备注');
            $table->timestamp('expired_at')->nullable()->comment('过期时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friendship_links');
    }
}
