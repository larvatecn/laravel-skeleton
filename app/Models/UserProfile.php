<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 个人资料
 * @property int $user_id 用户ID
 * @property string $timezone 用户时区
 * @property string $gender 性别
 * @property string $birthday 生日
 * @property string $country_code 国家ID
 * @property int $province_id 省ID
 * @property int $city_id 城市ID
 * @property int $district_id 区ID
 * @property string $address 联系地址
 * @property string $website 个人网站
 * @property string $introduction 个人简介
 * @property string $bio 个性签名
 *
 * @property User $user
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserProfile extends Model
{
    /**
     * @var bool 关闭时间戳
     */
    public $timestamps = false;

    /**
     * @var string 主键字段名
     */
    protected $primaryKey = 'user_id';

    /**
     * @var bool 关闭自增
     */
    public $incrementing = false;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'user_profiles';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'gender', 'birthday', 'country_code', 'province_id', 'city_id', 'district_id', 'address', 'website', 'introduction', 'bio'
    ];

    /**
     * 隐藏输出的属性
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
    ];

    /**
     * 获取签名
     * @return string
     */
    public function getBioAttribute(): string
    {
        if (empty($this->attributes['bio'])) {
            return trans('user.default_bio');
        }
        return $this->attributes['bio'];
    }

    /**
     * 获取个人简介
     * @return string
     */
    public function getIntroductionAttribute(): string
    {
        if (empty($this->attributes['introduction'])) {
            return '';
        }
        return $this->attributes['introduction'];
    }

    /**
     * Get the user relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 性别
     * @return string[]
     */
    public static function genders(): array
    {
        return [
            0 => '未知',
            1 => '男',
            2 => '女'
        ];
    }
}
