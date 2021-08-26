<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models\Traits;

use App\Models\User;
use App\Models\UserExtra;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * 用户关系
 * @mixin Model
 *
 * @property User $user
 * @property UserProfile $userProfile
 * @property UserExtra $userExtra
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
trait BelongsToUser
{
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
     * Get the user extra relation.
     *
     * @return BelongsTo
     */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_id', 'user_id');
    }

    /**
     * Get the user profile relation.
     *
     * @return BelongsTo
     */
    public function userExtra(): BelongsTo
    {
        return $this->belongsTo(UserExtra::class, 'user_id', 'user_id');
    }
}
