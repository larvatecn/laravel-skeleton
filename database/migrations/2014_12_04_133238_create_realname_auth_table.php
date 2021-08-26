<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealnameAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realname_auth', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->string('type', 20)->nullable()->comment('用户类型：personal 个人用户 enterprise 企业用户');

            $table->string('real_name')->nullable()->comment('真实姓名/企业名称');
            $table->string('identity', 100)->nullable()->comment('身份证号码/营业执照号码');

            $table->string('id_card_front')->nullable()->comment('证件正面照片');
            $table->string('id_card_back')->nullable()->comment('证件背面照片');
            $table->string('id_card_in_hand')->nullable()->comment('手持身份证照片');
            $table->string('license')->nullable()->comment('营业执照');

            $table->string('contact_person')->nullable()->comment('联系人姓名');
            $table->string('contact_mobile')->nullable()->comment('联系人手机号码');
            $table->string('contact_email')->nullable()->comment('联系人邮箱');

            $table->unsignedTinyInteger('status')->nullable()->default(0)->comment('认证状态：0 未提交 1 等待认证 2 认证通过 3 认证失败');
            $table->string('failed_reason')->nullable()->comment('失败描述');
            $table->timestamp('verified_at')->nullable()->comment('认证通过时间');
            $table->timestamp('submitted_at')->nullable()->comment('已提交时间');
            $table->timestamp('updated_at')->nullable()->comment('处理时间');

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
        Schema::dropIfExists('realname_auth');
    }
}
