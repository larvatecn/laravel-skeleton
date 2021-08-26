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
 * 使用时间戳作为主键
 * @mixin Model
 */
trait UsingTimestampAsPrimaryKey
{
    public static function bootUsingTimestampAsPrimaryKey(): void
    {
        static::creating(function (self $model): void {
            /* @var \Illuminate\Database\Eloquent\Model|\App\Models\Traits\UsingTimestampAsPrimaryKey $model */
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateKey();
            }
        });
    }

    /**
     * 生成主键
     * @return int
     */
    public function generateKey(): int
    {
        $i = rand(0, 9999);
        do {
            if (9999 == $i) {
                $i = 0;
            }
            $i++;
            $id = time() . str_pad((string)$i, 4, '0', STR_PAD_LEFT);
            $row = static::query()->where($this->primaryKey, '=', $id)->exists();
        } while ($row);
        return (int)$id;
    }

    /**
     * 关闭主键自增
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }
}
