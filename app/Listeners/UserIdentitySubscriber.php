<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Listeners;

use App\Events\IdentityApproved;
use App\Events\IdentityPending;
use App\Events\IdentityRejected;
use App\Notifications\SystemNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Lang;

class UserIdentitySubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * 处理等待认证事件
     * @param IdentityPending $event
     */
    public function handleIdentityPending(IdentityPending $event)
    {
        $event->identity->user->notify(new SystemNotification(Lang::get('Your real-name certification application has been submitted successfully.'), Lang::get('Your real-name certification application has been submitted successfully. Please pay attention to the notification on the site for the review result. Thank you for your support!')));
        //接下来可以委派任务
    }

    /**
     * 处理认证通过事件
     * @param IdentityApproved $event
     */
    public function handleIdentityApproved(IdentityApproved $event)
    {
        $event->identity->user->notify(new SystemNotification(Lang::get('Your real-name authentication has passed!'), Lang::get('Your real-name authentication has passed,Thank you for supporting us!')));
    }

    /**
     * 处理认证失败事件
     * @param IdentityRejected $event
     */
    public function handleIdentityRejected(IdentityRejected $event)
    {
        $event->identity->user->notify(new SystemNotification(Lang::get('Your real-name authentication audit failed!'), Lang::get('Your real-name authentication audit failed, please verify and apply again!')));
    }

    /**
     * 为事件订阅者注册监听器
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(IdentityPending::class, [UserIdentitySubscriber::class, 'handleIdentityPending']);
        $events->listen(IdentityApproved::class, [UserIdentitySubscriber::class, 'handleIdentityApproved']);
        $events->listen(IdentityRejected::class, [UserIdentitySubscriber::class, 'handleIdentityRejected']);
    }
}
