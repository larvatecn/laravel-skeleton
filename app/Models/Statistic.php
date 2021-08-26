<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 统计表
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Statistic extends Model
{
    public const TYPE_NEW_USER = 'new_user';
    public const TYPE_NEW_DEVICE_ANDROID = 'new_device_android';
    public const TYPE_NEW_DEVICE_IOS = 'new_device_ios';
    public const TYPE_NEW_ARTICLE = 'new_article';
    public const TYPE_NEW_BAIDU_PUSH = 'new_baidu_push';
    public const TYPE_NEW_BING_PUSH = 'new_bing_push';
    public const TYPE_NEW_SOCIAL_USER = 'new_social_user';

    public const TYPE_WECHAT_USER_CUMULATE = 'wechat_user_cumulate';

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'statistics';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'type', 'date', 'quantity'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 查询指定日期范围内的统计
     * @param string $type
     * @param string $beginDate
     * @param string $endDate
     * @return array
     */
    public static function getDatacube(string $type, string $beginDate, string $endDate): array
    {
        $data = [];
        $beginDate = Carbon::createFromFormat('Y-m-d', $beginDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);
        $data['quantity'] = static::query()->where('type', $type)
            ->where('date', '>=', $beginDate)
            ->where('date', '<=', $endDate)
            ->sum('quantity');
        $data['data'] = static::query()->select(['quantity'])->where('type', $type)
            ->where('date', '>=', $beginDate)
            ->where('date', '<=', $endDate)
            ->pluck('quantity');
        return $data;
    }

    /**
     * 获取当前日期到之前多少天之间的统计数
     * @param string $type 类别
     * @param int|string $days
     * @return array
     */
    public static function getTimingHistory(string $type, $days): array
    {
        $days = (int)$days;
        $data = [];
        $data['quantity'] = static::query()->where('type', $type)
            ->where('date', '<=', Carbon::now())->where('date', '>=', Carbon::today()->subDays($days))
            ->sum('quantity');
        $data['data'] = static::query()->select(['quantity'])->where('type', $type)
            ->where('date', '<=', Carbon::now())->where('date', '>=', Carbon::today()->subDays($days))
            ->pluck('quantity');
        return $data;
    }
}
