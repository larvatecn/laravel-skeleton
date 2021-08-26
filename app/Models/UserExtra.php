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
use Illuminate\Support\Carbon;
use Vinkla\Hashids\Facades\Hashids;

/**
 * 用户扩展资料
 * @property int $user_id 用户ID
 * @property int $referrer_id 邀请人ID
 * @property string $invite_code 我的邀请码
 * @property string $login_ip 最后登录IP
 * @property Carbon|null $login_at 最后登录时间
 * @property int $login_num 登录次数
 * @property Carbon|null $first_sign_in_at 开始签到时间
 * @property int $views 用户被查看次数
 * @property int $collections 用户收藏数
 * @property int $articles 用户文章数
 *
 * @property User $user
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserExtra extends Model
{
    use Traits\DateTimeFormatter;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'user_extras';

    /**
     * @var string 主键
     */
    protected $primaryKey = 'user_id';

    /**
     * @var bool 关闭主键自增
     */
    public $incrementing = false;

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'referrer_id', 'invite_code', 'login_ip', 'login_at', 'login_num', 'first_sign_in_at', 'invite_count',
        'views', 'collections', 'articles',
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
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'login_num' => 0,
        'invite_count' => 0,
        'views' => 0,
        'collections' => 0,
        'articles' => 0,
    ];

    /**
     * 应该被转化为原生类型的属性
     *
     * @var array
     */
    protected $casts = [
        'referrer_id' => 'int',
        'login_num' => 'int',
        'invite_count' => 'int',
        'views' => 'int',
        'collections' => 'int',
        'articles' => 'int',
        'first_sign_in_at' => 'datetime',
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'login_at',
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (UserExtra $model) {
            $model->invite_code = Hashids::connection('invite')->encode($model->user_id);
        });
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
     * 计数器增加
     * @param int|string $user_id
     * @param string $column
     * @param float|int $amount
     * @param array $extra
     * @return int
     */
    public static function inc($user_id, string $column, $amount = 1, array $extra = []): int
    {
        return static::query()->where('user_id', $user_id)->increment($column, $amount, $extra);
    }

    /**
     * 计数器减少
     * @param int|string $user_id
     * @param string $column
     * @param float|int $amount
     * @param array $extra
     * @return int
     */
    public static function dec($user_id, string $column, $amount = 1, array $extra = []): int
    {
        return static::query()->where('user_id', $user_id)->where($column, '>', 0)->decrement($column, $amount, $extra);
    }
}
