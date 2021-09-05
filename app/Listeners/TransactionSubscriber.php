<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Larva\Transaction\Events\ChargeClosed;
use Larva\Transaction\Events\ChargeFailed;
use Larva\Transaction\Events\ChargeSucceeded;
use Larva\Transaction\Events\RefundFailed;
use Larva\Transaction\Events\RefundSucceeded;
use Larva\Transaction\Events\TransferFailed;
use Larva\Transaction\Events\TransferSucceeded;

/**
 * 交易观察者
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TransactionSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * 付款关闭
     * @param ChargeClosed $event
     */
    public function handleChargeClosed(ChargeClosed $event)
    {
    }

    /**
     * 付款失败
     * @param ChargeFailed $event
     */
    public function handleChargeFailed(ChargeFailed $event)
    {
    }

    /**
     * 付款成功
     * @param ChargeSucceeded $event
     */
    public function handleChargeSucceeded(ChargeSucceeded $event)
    {
        if ($event->charge->order instanceof \App\Models\Recharge) {
            $event->charge->order->markSucceed();
        }
    }

    /**
     * 退款失败
     * @param RefundFailed $event
     */
    public function handleRefundFailed(RefundFailed $event)
    {
    }

    /**
     * 退款成功
     * @param RefundSucceeded $event
     */
    public function handleRefundSucceeded(RefundSucceeded $event)
    {
    }

    /**
     * 企业付款失败
     * @param TransferFailed $event
     */
    public function handleTransferFailed(TransferFailed $event)
    {
    }

    /**
     * 企业付款成功
     * @param TransferSucceeded $event
     */
    public function handleTransferSucceeded(TransferSucceeded $event)
    {
    }

    /**
     * 为事件订阅者注册监听器
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ChargeClosed::class, [TransactionSubscriber::class, 'handleChargeClosed']);
        $events->listen(ChargeFailed::class, [TransactionSubscriber::class, 'handleChargeFailed']);
        $events->listen(ChargeSucceeded::class, [TransactionSubscriber::class, 'handleChargeSucceeded']);
        $events->listen(RefundFailed::class, [TransactionSubscriber::class, 'handleRefundFailed']);
        $events->listen(RefundSucceeded::class, [TransactionSubscriber::class, 'handleRefundSucceeded']);
        $events->listen(TransferFailed::class, [TransactionSubscriber::class, 'handleTransferFailed']);
        $events->listen(TransferSucceeded::class, [TransactionSubscriber::class, 'handleTransferSucceeded']);
    }
}
