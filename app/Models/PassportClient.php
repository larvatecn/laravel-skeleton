<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Services\FileService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Passport\Client;

/**
 * Class PassportClient
 * @property int $id
 * @property string $name
 * @property string $secret
 * @property string $redirect
 * @property boolean $personal_access_client
 * @property boolean $password_client
 * @property boolean $revoked
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PassportClient extends Client
{
    use Traits\DateTimeFormatter;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //'secret',
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'personal_access_client' => false,
        'password_client' => false,
        'revoked' => false
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->secret)) {
                $model->secret = Str::random(40);
            }
        });

        static::updating(function ($model) {
            if (empty($model->secret)) {
                $model->secret = Str::random(40);
            }
        });
    }

    /**
     * 获取Logo
     * @return string
     */
    public function getLogoAttribute(): string
    {
        if (!empty($this->attributes['logo_path'])) {
            return FileService::make()->url($this->attributes['logo_path']);
        }
        return asset('img/default_picture.png');
    }

    /**
     * 客户端是否应跳过授权提示
     *
     * @return bool
     */
    public function skipsAuthorization(): bool
    {
        return $this->firstParty();
    }
}
