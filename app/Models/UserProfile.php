<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 用户个人资料
 *
 * @property int $user_id 用户ID
 * @property int $gender 性别：0保密/1男/2女
 * @property Carbon $birthday 生日
 * @property int $company_id 公司ID
 * @property int $province_id 省 ID
 * @property int $city_id 市 ID
 * @property int $area_id 区县ID
 * @property string $website 个人网站
 * @property string $intro 个人介绍
 * @property string $bio 个性签名
 *
 * 关系对象
 * @property User $user 用户实例
 *
 * @author Tongle Xu <xutongle@msn.com>
 */
class UserProfile extends Model
{
    use Traits\DateTimeFormatter;

    public const GENDER_UNKNOWN = 0; //未知

    public const GENDER_MALE = 1; //男

    public const GENDER_FEMALE = 2; //女

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_profiles';

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
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gender', 'birthday', 'company_id', 'province_id', 'city_id', 'area_id', 'website', 'intro', 'bio',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'gender' => self::GENDER_UNKNOWN,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'int',
        'gender' => 'int',
        'birthday' => 'datetime',
        'company_id' => 'int',
        'province_id' => 'int',
        'city_id' => 'int',
        'area_id' => 'int',
        'website' => 'string',
        'intro' => 'string',
        'bio' => 'string',
    ];

    /**
     * Get the user relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
