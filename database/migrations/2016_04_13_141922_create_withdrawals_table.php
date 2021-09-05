<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
use App\Models\Withdrawals;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->comment('提现 id');
            $table->unsignedBigInteger('user_id')->comment('用户 id');
            $table->string('trade_channel', 30)->comment('提现渠道');
            $table->unsignedInteger('amount')->default(0)->comment('提现金额');//单位：分
            $table->string('status', 10)->default(Withdrawals::STATUS_PENDING)->comment('提现状态');
            $table->string('recipient');
            $table->json('attach')->nullable()->comment('附加参数');
            $table->ipAddress('client_ip')->nullable()->comment('提现IP');//发起支付请求客户端的 IP 地址
            $table->timestamp('canceled_at', 0)->nullable()->comment('提现取消时间');//成功时间
            $table->timestamp('succeed_at', 0)->nullable()->comment('提现成功时间');//成功时间
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
        Schema::dropIfExists('withdrawals');
    }
}
