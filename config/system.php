<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
return [
    'mobile_rule' => '/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/',
    'tel_rule' => '/^0\d{2,3}-\d{7,8}/',
    'domain_rule' => '/^([a-z0-9-.]*)\.([a-z]{2,8})$/i',
    'system_user_ids' => [
        10000000, 10000001
    ],
];
