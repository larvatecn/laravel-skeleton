<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0);
            $table->string('name', 30);
            $table->string('type', 20)->index('type');
            $table->string('slug', 30)->nullable();
            $table->string('thumb_path')->nullable();
            $table->string('title')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description', 1000)->nullable();
            $table->smallInteger('order')->default(0)->comment('栏目排序');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('categories');
    }
}
