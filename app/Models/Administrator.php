<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 管理员表
 * @property int $id
 * @property int $user_id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $avatar
 *
 * @property User $user
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Administrator extends \Dcat\Admin\Models\Administrator
{
    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function (Administrator $model) {
            $user = User::createByUsername($model->username, $model->password);
            $model->user_id = $user->id;
            $model->saveQuietly();
        });
        static::updated(function (Administrator $model) {
            $model->user->password = $model->password;
            $model->user->saveQuietly();
        });
    }

    /**
     * 获取用户资料
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get avatar attribute.
     *
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->user->avatar;
    }

    /**
     * 设置头像
     * @param string|null $avatar
     */
    public function setAvatarAttribute(?string $avatar)
    {
        $this->attributes['avatar'] = $avatar;
        $this->user->avatar_path = $avatar;
        $this->user->saveQuietly();
    }
}
