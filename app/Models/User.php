<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * 用户模型
 *
 * @property int $id 用户ID
 * @property string $username 用户名
 * @property string|null $email 邮件地址
 * @property string|null $phone 手机号
 * @property string $nickname 昵称
 * @property string $avatar 头像
 * @property int $status 状态：0:active,1:frozen
 * @property string $password 密码哈希
 * @property string $remember_token 记住我 Token
 * @property Carbon $created_at 注册时间
 * @property Carbon $updated_at 最后更新时间
 * @property Carbon|null $deleted_at 删除时间
 *
 * 只读属性
 * @property-read string|null $phone_display 显示手机号
 *
 * 关系对象
 * @property UserProfile $profile 个人信息
 * @property UserExtra $extra 用户扩展信息
 * @property LoginHistory[] $loginHistories 登录历史
 *
 * @author Tongle Xu <xutongle@msn.com>
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use Traits\DateTimeFormatter;

    //默认头像
    public const DEFAULT_AVATAR = 'img/avatar.jpg';

    // 用户状态
    public const STATUS_ACTIVE = 0; //正常

    public const STATUS_FROZEN = 1; //已冻结

    public const STATUSES = [
        self::STATUS_ACTIVE => '正常',
        self::STATUS_FROZEN => '已冻结',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 'email', 'phone', 'nickname', 'avatar', 'status', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'int',
        'username' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'nickname' => 'string',
        'avatar' => 'string',
        'status' => 'int',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Perform any actions required after the model boots.
     */
    protected static function booted(): void
    {
        static::saving(function (User $model) {
            $model->email = $model->email ?? null;
            $model->username = $model->username ?? $model->email;
            $model->nickname = $model->nickname ?? $model->username;
        });
        static::created(function (User $model) {
            $model->profile()->create();
            $model->extra()->create();
        });
        static::forceDeleted(function (User $model) {
            $model->profile->delete();
            $model->extra->delete();
            $model->loginHistories()->delete();
        });
    }

    /**
     * 获取昵称
     */
    protected function nickname(): Attribute
    {
        return Attribute::make(get: function (?string $value, $attributes) {
            return $value ?: $attributes['username'];
        });
    }

    /**
     * 获取手机号
     */
    protected function phoneDisplay(): Attribute
    {
        return Attribute::make(
            get: function (?string $value, $attributes) {
                return $attributes['phone'] ? substr_replace($attributes['phone'], '****', 3, 4) : '';
            },
        );
    }

    /**
     * Get the profile relation.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the extra relation.
     */
    public function extra(): HasOne
    {
        return $this->hasOne(UserExtra::class);
    }

    /**
     * Get the login histories relation.
     */
    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * 查询活的用户
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '=', static::STATUS_ACTIVE);
    }

    /**
     * 获取手机号
     *
     * @param  \Illuminate\Notifications\Notification|null  $notification
     */
    public function routeNotificationForPhone($notification): ?string
    {
        return $this->phone ?: null;
    }
}
