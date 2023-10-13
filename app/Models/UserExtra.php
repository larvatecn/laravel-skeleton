<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * 用户扩展信息
 *
 * @property int $user_id 用户ID
 * @property int|null $referrer_id 推荐人ID
 * @property string $last_login_ip 最后登录IP
 * @property int $invite_count 邀请人数
 * @property string $invite_code 邀请码
 * @property int $username_change_count 用户名修改次数
 * @property int $login_count 登录次数
 * @property Carbon $first_active_at 首次活动时间
 * @property Carbon $last_active_at 最后活动时间
 * @property Carbon|null $last_login_at 最后登录时间
 * @property Carbon|null $phone_verified_at 手机号验证时间
 * @property Carbon|null $email_verified_at 邮箱验证时间
 *
 * 关系对象
 * @property User $user 用户实例
 *
 * @author Tongle Xu <xutongle@msn.com>
 */
class UserExtra extends Model
{
    use Traits\DateTimeFormatter;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_extras';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'referrer_id', 'last_login_ip', 'invite_count', 'invite_code', 'username_change_count', 'login_count',
        'support_count', 'collection_count', 'first_sign_in_at', 'first_active_at', 'last_active_at', 'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'int',
        'referrer_id' => 'int',
        'last_login_ip' => 'string',
        'invite_count' => 'int',
        'invite_code' => 'string',
        'username_change_count' => 'int',
        'login_count' => 'int',
        'support_count' => 'int',
        'collection_count' => 'int',
        'first_sign_in_at' => 'datetime',
        'last_active_at' => 'datetime',
        'last_login_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Perform any actions required after the model boots.
     */
    protected static function booted(): void
    {
        static::creating(function (UserExtra $model) {
            $model->invite_code = Str::ulid();
        });
    }

    /**
     * Get the user relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
