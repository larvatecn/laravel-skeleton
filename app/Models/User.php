<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Jobs\SocialAvatarDownloadJob;
use App\Services\FileService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\HasApiTokens;
use Larva\Socialite\Models\SocialUser;

/**
 * 用户模型
 * @property int $id ID
 * @property string $mobile 手机号
 * @property string $username 昵称
 * @property string $email 邮箱
 * @property string $password 密码
 * @property string $remember_token 记住我
 * @property string $avatar_path 头像路径
 * @property boolean $identified 是否经过实名认证
 * @property int $status 用户状态：0 正常 1 禁用
 * @property int $available_amount 可用余额
 * @property int $score 积分
 * @property Carbon|null $mobile_verified_at 手机验证时间
 * @property Carbon|null $email_verified_at 邮箱验证时间
 * @property Carbon $created_at 注册时间
 * @property Carbon $updated_at 最后更新时间
 * @property Carbon|null $deleted_at 删除时间
 * @property-read string $avatar 头像Url
 * @property-read boolean $hasAvatar 是否有头像
 *
 * @property UserExtra $extra 扩展信息
 * @property UserProfile $profile 个人信息
 * @property SocialUser[] $socials 社交账户
 * @property Device[] $devices 移动设备
 * @property LoginHistory[] $loginHistories 登录历史
 * @property Administrator $administrator 管理员实例
 * @property RealnameAuth $realnameAuth 实名认证
 * @property Score[] $scores 积分交易记录
 * @property NotificationSettings[] $notificationSettings
 *
 * @method static Builder|User active()
 * @method static null|User find($id)
 * @method static User[] all()
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use Traits\DateTimeFormatter;

    // 用户状态
    public const STATUS_NORMAL = 0;
    public const STATUS_DISABLED = 1;
    /**
     * 模型数据表
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * 允许批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'mobile', 'password', 'avatar_path', 'identified', 'status', 'score', 'available_amount',
    ];

    /**
     * 隐藏输出的属性
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 属性类型转换
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at',
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_NORMAL,
        'available_amount' => 0,
        'score' => 0,
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function (User $model) {
            $model->profile()->create();//创建Profile
            $model->extra()->create();//创建Extra
            $model->realnameAuth()->create();//初始化实名认证
        });
        static::forceDeleted(function (User $model) {
            $model->profile->delete();
            $model->extra->delete();
            $model->socials()->delete();
            $model->loginHistories()->delete();
            $model->notifications()->delete();
            $model->realnameAuth()->delete();
        });
    }

    /**
     * 获取用户资料
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * 获取用户扩展资料
     * @return HasOne
     */
    public function extra(): HasOne
    {
        return $this->hasOne(UserExtra::class);
    }

    /**
     * 获取用户已经绑定的社交账户
     * @return HasMany
     */
    public function socials(): HasMany
    {
        return $this->hasMany(SocialUser::class);
    }

    /**
     * 实名认证
     * @return hasOne
     */
    public function realnameAuth(): hasOne
    {
        return $this->hasOne(RealnameAuth::class);
    }

    /**
     * 获取登录历史
     * @return HasMany
     */
    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * 获取用户设备列表
     * @return HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * 获取用户签到记录
     * @return HasMany
     */
    public function signs(): HasMany
    {
        return $this->hasMany(SignIn::class);
    }

    /**
     * 积分交易明细
     * @return HasMany
     */
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Get the admin relation.
     *
     * @return BelongsTo
     */
    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'id', 'user_id');
    }

    /**
     * 通知渠道
     * @return HasMany
     */
    public function notificationSettings(): HasMany
    {
        return $this->hasMany(NotificationSettings::class);
    }

    /**
     * 查询活的用户
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '=', static::STATUS_NORMAL);
    }

    /**
     * 获取通知渠道
     * @param string|null $category
     * @param array $default
     * @return array
     */
    public function getNotifyChannels(?string $category, array $default = []): array
    {
        if (is_null($category)) {
            return $default;
        }
        /** @var NotificationSettings $settings */
        if (($settings = $this->notificationSettings()->where('category', $category)->first()) != null) {
            return array_keys($settings->channels);
        }
        return $default;
    }

    /**
     * 是否有头像
     * @return boolean
     */
    public function getHasAvatarAttribute(): bool
    {
        return !empty($this->avatar_path);
    }

    /**
     * 返回头像Url
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        $avatar = $this->avatar_path;
        if (!empty($avatar)) {
            return FileService::make()->url($avatar);
        }
        return asset('img/avatar.jpg');
    }

    /**
     * 设置头像
     * @param string|null $avatarPath
     * @return bool
     */
    public function setAvatar(?string $avatarPath): bool
    {
        if ($this->administrator) {
            $this->administrator->avatar = $avatarPath;
            $this->administrator->saveQuietly();
        }
        return $this->forceFill([
            'avatar_path' => $avatarPath
        ])->saveQuietly();
    }

    /**
     * 获取手机号
     * @param \Illuminate\Notifications\Notification|null $notification
     * @return string|null
     */
    public function routeNotificationForMobile($notification): ?string
    {
        return $this->mobile;
    }

    /**
     * 获取移动端设备
     * @param \Illuminate\Notifications\Notification|null $notification
     * @return Device|null
     */
    public function routeNotificationForDevice($notification): ?Device
    {
        return Device::byUser($this->id)->latest('id')->first();
    }


    /**
     * 发送邮箱验证通知
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        if (!is_null($this->email)) {
            parent::sendEmailVerificationNotification();
        }
    }

    /**
     * Determine if the user has verified their mobile number.
     *
     * @return bool
     */
    public function hasVerifiedMobile(): bool
    {
        return !is_null($this->mobile_verified_at);
    }

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified(): bool
    {
        $status = $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->saveQuietly();
        Event::dispatch(new \App\Events\MobileVerified($this));
        return $status;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified(): bool
    {
        $status = parent::markEmailAsVerified();
        Event::dispatch(new \App\Events\EmailVerified($this));
        return $status;
    }

    /**
     * Mark the given user's active.
     *
     * @return bool
     */
    public function markActive(): bool
    {
        return $this->forceFill([
            'status' => static::STATUS_NORMAL,
        ])->save();
    }

    /**
     * Mark the given user's disabled.
     *
     * @return bool
     */
    public function markDisabled(): bool
    {
        return $this->forceFill([
            'status' => static::STATUS_DISABLED,
        ])->save();
    }

    /**
     * Determine if the user has active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == static::STATUS_NORMAL;
    }

    /**
     * 重置用户密码
     *
     * @param string $password
     * @return void
     */
    public function resetPassword(string $password)
    {
        $this->password = \Illuminate\Support\Facades\Hash::make($password);
        $this->setRememberToken(\Illuminate\Support\Str::random(60));
        $this->saveQuietly();
        Event::dispatch(new \Illuminate\Auth\Events\PasswordReset($this));
    }

    /**
     * 重置用户手机号
     * @param string|int $mobile
     * @return bool
     */
    public function resetMobile($mobile): bool
    {
        $status = $this->forceFill([
            'mobile' => $mobile,
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->saveQuietly();
        Event::dispatch(new \App\Events\MobileReset($this));
        return $status;
    }

    /**
     * 重置用户邮箱
     * @param string $email
     * @return bool
     */
    public function resetEmail(string $email): bool
    {
        $status = $this->forceFill([
            'email' => $email,
            'email_verified_at' => $this->freshTimestamp(),
        ])->saveQuietly();
        Event::dispatch(new \App\Events\EmailReset($this));
        return $status;
    }

    /**
     * 更新最后登录
     * @param string $clientIp
     * @param string|null $userAgent
     */
    public function updateLogin(string $clientIp, string $userAgent = null)
    {
        $this->extra()->increment('login_num', 1, [
            'login_at' => $this->fromDateTime($this->freshTimestamp()),
            'login_ip' => $clientIp
        ]);
        $this->loginHistories()->create([
            'ip' => $clientIp,
            'user_agent' => $userAgent
        ]);
    }

    /**
     * 查找用户
     * @param string $username
     * @return mixed
     */
    public function findForPassport(string $username)
    {
        if (preg_match(config('system.mobile_rule'), $username)) {
            return static::active()
                ->where('mobile', $username)
                ->first();
        } elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return static::active()
                ->where('email', $username)
                ->first();
        } else {
            return static::active()
                ->where('username', $username)
                ->first();
        }
    }

    /**
     * 关联推荐人
     * @param string|null $inviteCode
     * @return bool
     */
    public function connectReferrer(?string $inviteCode): bool
    {
        if ($inviteCode) {
            $inviteUserId = UserExtra::where('invite_code', $inviteCode)->value('user_id');
            $status = $this->extra->saveQuietly(['referrer_id' => $inviteUserId]);
            UserExtra::inc($inviteUserId, 'invite_count');
            return $status;
        }
        return false;
    }

    /**
     * 通过邀请码获取用户
     * @param string $inviteCode
     * @return User|null
     */
    public static function findByInviteCode(string $inviteCode): ?User
    {
        $extra = UserExtra::with('user')->where('invite_code', $inviteCode)->first();
        return $extra->user ?? null;
    }

    /**
     * 随机生成一个用户名
     * @param string $username 用户名
     * @return string
     */
    public static function generateUsername(string $username): string
    {
        if (static::query()->where('username', '=', $username)->exists()) {
            $row = static::query()->max('id');
            $username = $username . ++$row;
        }
        return $username;
    }

    /**
     * 通过手机创建用户
     * @param string|int $mobile
     * @param string $password
     * @return User
     */
    public static function createByMobile($mobile, string $password): User
    {
        $username = static::generateUsername('m' . $mobile);
        /** @var User $user */
        $user = static::create([
            'username' => $username,
            'mobile' => $mobile,
            'password' => \Illuminate\Support\Facades\Hash::make($password)
        ]);
        $user->markMobileAsVerified();
        return $user;
    }

    /**
     * 通过用户名创建用户
     * @param string $username
     * @param string $password
     * @return User
     */
    public static function createByUsername(string $username, string $password): User
    {
        $username = static::generateUsername($username);
        return static::create([
            'username' => $username,
            'password' => \Illuminate\Support\Facades\Hash::make($password)
        ]);
    }

    /**
     * 通过邮箱创建用户
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function createByEmail(string $email, string $password): User
    {
        $emailArr = explode('@', $email);
        $username = static::generateUsername($emailArr[0]);
        return static::createByUsernameAndEmail($username, $email, $password);
    }

    /**
     * 通过用户名和邮箱创建用户
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function createByUsernameAndEmail(string $username, string $email, string $password): User
    {
        $username = static::generateUsername($username);
        return static::create([
            'username' => $username,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
        ]);
    }
}
