<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Models\Traits;

use App\Models\User;
use App\Models\UserExtra;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 用户关系
 *
 * @property User $user 用户实例
 * @property UserProfile $userProfile 用户资料
 * @property UserExtra $userExtra 用户扩展资料
 *
 * @method static Builder forUser($user)
 *
 * @mixin Model
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
trait BelongsToUser
{
    /**
     * Get the user relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'nickname' => null,
            'username' => null,
            'email' => null,
            'phone' => null,
        ]);
    }

    /**
     * Get the user profile relation.
     */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_id', 'user_id');
    }

    /**
     * Get the user extra relation.
     */
    public function userExtra(): BelongsTo
    {
        return $this->belongsTo(UserExtra::class, 'user_id', 'user_id');
    }

    /**
     * 查询指定用户
     */
    public function scopeForUser(Builder $query, User|int|string $user): Builder
    {
        if ($user instanceof User) {
            return $query->where('user_id', $user->id);
        }

        return $query->where('user_id', $user);
    }
}
