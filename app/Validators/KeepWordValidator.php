<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Validators;

/**
 * 验证是否含有禁止的词语
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class KeepWordValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return !in_array($value, config('filter.words'));
    }
}
