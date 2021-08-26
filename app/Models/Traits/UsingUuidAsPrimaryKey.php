<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * 使用UUID作为主键
 * @mixin Model
 */
trait UsingUuidAsPrimaryKey
{
    public static function bootUsingUuidAsPrimaryKey(): void
    {
        static::creating(function (self $model): void {
            /* @var \Illuminate\Database\Eloquent\Model|\App\Models\Traits\UsingUuidAsPrimaryKey $model */
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::orderedUuid()->toString();
            }
        });
    }

    /**
     * 关闭主键自增
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * 主键类型
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
