<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Validators;

/**
 * Class UsernameValidator
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UsernameValidator
{
    /**
     * @var string
     */
    //public $pattern = '/^[-a-zA-Z0-9_]+$/u';
    public $pattern = '/^[-a-zA-Z0-9_\x{4e00}-\x{9fa5}\.@]+$/u';


    public function validate($attribute, $value, $parameters, $validator)
    {
        return preg_match($this->pattern, $value);
    }
}
