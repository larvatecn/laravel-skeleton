<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 用户设备实例
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $os
 * @property string $imei
 * @property string $imsi
 * @property string $model
 * @property string $vendor
 * @property string $version
 * @property-read boolean $isAndroid
 * @property-read boolean $isIOS
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|Device byUser($userId)
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Device extends Model
{
    use Traits\BelongsToUser;
    use Traits\DateTimeFormatter;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'devices';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'token', 'os', 'imei', 'imsi', 'model', 'vendor', 'version'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * 链接用户
     * @param User $user
     * @return bool
     */
    public function connect(User $user): bool
    {
        return $this->update(['user_id' => $user->getAuthIdentifier()]);
    }

    /**
     * Finds an account by user_id.
     * @param Builder $query
     * @param int|string $userId
     * @return Builder
     */
    public function scopeByUser(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * 获取设备
     * @param array $deviceAttributes
     * @return $this
     */
    public static function findDevice(array $deviceAttributes): Device
    {
        if (($device = static::where('token', $deviceAttributes['token'])->first()) == null) {
            $device = static::create($deviceAttributes);
        }
        return $device;
    }

    /**
     * 是否是安卓
     * @return bool
     */
    public function getIsAndroidAttribute(): bool
    {
        return strtolower($this->os) == 'android';
    }

    /**
     * 是否是IOS
     * @return bool
     */
    public function getIsIOSAttribute(): bool
    {
        return strtolower($this->os) == 'ios';
    }
}
