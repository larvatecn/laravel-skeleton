<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Casts\NotificationChannels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 通知设置
 * @property int $id ID
 * @property int $user_id 用户ID
 * @property string $category 通知类别
 * @property array $channels 通知渠道设置
 * @property Carbon $updated_at 更新时间
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class NotificationSettings extends Model
{
    use Traits\BelongsToUser;
    use Traits\DateTimeFormatter;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_settings';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category', 'channels',
    ];

    /**
     * 属性类型转换
     *
     * @var array
     */
    protected $casts = [
        'channels' => NotificationChannels::class,
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'updated_at'
    ];
}
