<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 系统通知
 * @author Tongle Xu <xutongle@gmail.com>
 */
class SystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The subject.
     *
     * @var string
     */
    public $subject;

    /**
     * The content.
     *
     * @var string
     */
    public $content;

    /**
     * Create a new notification instance.
     *
     * @param string $subject
     * @param string $content
     */
    public function __construct(string $subject, string $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject($this->subject)
            ->line($this->content);
    }

    /**
     * Get the database of the notification.
     *
     * @return array
     */
    public function toDatabase(): array
    {
        return [
            'subject' => $this->subject,
            'content' => $this->content
        ];
    }
}
