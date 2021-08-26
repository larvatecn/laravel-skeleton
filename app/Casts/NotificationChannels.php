<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class NotificationChannels implements CastsAttributes
{
    /**
     * 默认值
     * @var array
     */
    protected array $defaultValue = [
        'database',
    ];

    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes): array
    {
        $value = json_decode($value, true);
        return array_intersect($this->defaultValue, is_array($value) ? $value : []);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        return json_encode(array_intersect($this->defaultValue, is_array($value) ? $value : []));
    }
}
