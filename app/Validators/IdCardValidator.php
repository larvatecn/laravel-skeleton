<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Validators;

use Larva\Support\IDCard;

/**
 * 中国大陆居民身份证验证
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IdCardValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return IDCard::validateCard($value);
    }
}
