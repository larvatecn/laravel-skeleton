<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Models\Traits;

use DateTimeInterface;

/**
 * 默认日期格式
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
trait DateTimeFormatter
{
    /**
     * 为数组 / JSON 序列化准备日期。
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }
}
