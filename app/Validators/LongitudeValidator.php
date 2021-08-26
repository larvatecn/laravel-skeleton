<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Validators;

/**
 * Class LongitudeValidator
 * @author Tongle Xu <xutongle@gmail.com>
 */
class LongitudeValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        if ($value < -180 || $value > 180) {
            return false;
        }
        return true;
    }
}
