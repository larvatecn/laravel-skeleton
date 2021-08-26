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
 * 使用表名作为 Morph 类
 * @mixin Model
 * @author Tongle Xu <xutongle@gmail.com>
 */
trait UseTableNameAsMorphClass
{
    /**
     * 使用数据表名的单数作为 Morph Type
     * @return string
     */
    public function getMorphClass(): string
    {
        return Str::singular($this->getTable());
    }
}
