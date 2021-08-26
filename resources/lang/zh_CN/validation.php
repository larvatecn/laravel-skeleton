<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute必须接受。',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => ':attribute必须是一个有效的网址。',
    'after' => ':attribute必须是一个在:date之后的日期。',
    'after_or_equal' => ':attribute必须是等于或小于:date的日期。',
    'alpha' => ':attribute只能由字母组成。',
    'alpha_dash' => ':attribute只能由字母、数字和斜杠组成。',
    'alpha_num' => ':attribute只能由字母和数字组成。',
    'array' => ':attribute必须是一个数组。',
    'before' => ':attribute必须是一个在:date之前的日期。',
    'before_or_equal' => ':attribute必须是早于:date的日期。',
    'between' => [
        'numeric' => ':attribute必须介于:min - :max之间。',
        'file' => ':attribute必须介于:min - :maxkb 之间。',
        'string' => ':attribute必须介于:min - :max个字符之间。',
        'array' => ':attribute必须只有:min - :max个单元。',
    ],
    'boolean' => ':attribute 必须为布尔值。',
    'confirmed' => ':attribute两次输入不一致。',
    'current_password' => '密码不正确。',
    'date' => ':attribute 不是一个有效的日期。',
    'date_equals' => ':attribute必须是等于:date的日期。',
    'date_format' => ':attribute的格式必须为:format。',
    'different' => ':attribute和:other必须不同。',
    'digits' => ':attribute必须是:digits位的数字。',
    'digits_between' => ':attribute必须是介于:min和:max位的数字。',
    'dimensions' => ':attribute的图片尺寸无效。',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => ':attribute不是一个合法的邮箱。',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => ':attribute不存在。',
    'file' => ':attribute必须是个文件。',
    'filled' => ':attribute不能为空。',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => ':attribute必须是图片。',
    'in' => '已选的属性:attribute不合法。',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => ':attribute必须是整数。',
    'ip' => ':attribute必须是有效的IP地址。',
    'ipv4' => ':attribute必须是有效的 IPv4 地址。',
    'ipv6' => ':attribute必须是有效的 IPv6 地址。',
    'json' => ':attribute必须是正确的 JSON 格式。',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => ':attribute不能大于:max。',
        'file' => ':attribute不能大于:max kb。',
        'string' => ':attribute不能大于:max个字符。',
        'array' => ':attribute最多只有:max个单元。',
    ],
    'mimes' => ':attribute必须是:values类型的文件。',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => ':attribute必须大于等于:min。',
        'file' => ':attribute 大小不能小于:min kb。',
        'string' => ':attribute 至少为:min个字符。',
        'array' => ':attribute 至少有:min个单元。',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute必须是一个数字。',
    'password' => '密码错误。',
    'present' => ':attribute字段必须存在。',
    'regex' => ':attribute格式不正确。',
    'required' => ':attribute不能为空。',
    'required_if' => '当:other为:value时:attribute不能为空。',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => '当:values存在时:attribute不能为空。',
    'required_with_all' => '当:values存在时 :attribute不能为空。',
    'required_without' => '当:values不存在时 :attribute不能为空。',
    'required_without_all' => '当:values都不存在时:attribute不能为空。',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'same' => ':attribute和:other必须相同。',
    'size' => [
        'numeric' => ':attribute大小必须为:size。',
        'file' => ':attribute大小必须为:sizekb。',
        'string' => ':attribute必须是:size个字符。',
        'array' => ':attribute必须为:size个单元。',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => ':attribute必须是一个字符串。',
    'timezone' => ':attribute必须是一个合法的时区值。',
    'unique' => ':attribute已经存在。',
    'uploaded' => ':attribute上传失败。',
    'url' => ':attribute格式不正确。',
    'uuid' => ':attribute必须是有效的 UUID。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'ticket' => [
            'required' => '请先通过人机验证。',
            'ticket' => '人机验证失败，请重试。',
        ],
        'mobile' => [
            'required' => '请输入手机号码。',
            'mobile' => '手机号码不正确，请重新输入。',
        ],
        'verify_code' => [
            'required' => '请输入验证码。',
            'mobile_verify_code' => '短信验证码不正确。',
            'mail_verify_code' => '邮件验证码不正确。',
        ],
        'id_card' => [
            'required' => '请输入证件号码。',
            'id_card' => '证件号码不正确，请重新输入。',
        ],
        'username' => [
            'required' => '请输入用户名。',
            'username' => '用户名不正确，请重新输入。',
        ],
        'captcha' => [
            'required' => '请输入验证码。',
            'captcha' => '验证码不正确。',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'address' => '地址',
        'age' => '年龄',
        'available' => '可用的',
        'city' => '城市',
        'content' => '内容',
        'country' => '国家',
        'date' => '日期',
        'day' => '天',
        'description' => '描述',
        'email' => '邮箱',
        'excerpt' => '摘要',
        'first_name' => '名',
        'gender' => '性别',
        'hour' => '时',
        'last_name' => '姓',
        'minute' => '分',
        'mobile' => '手机',
        'month' => '月',
        'name' => '名称',
        'password' => '密码',
        'password_confirmation' => '确认密码',
        'phone' => '电话',
        'second' => '秒',
        'sex' => '性别',
        'size' => '大小',
        'time' => '时间',
        'title' => '标题',
        'username' => '用户名',
        'year' => '年',
        'account' => '账号',
        'captcha' => '验证码',
        'id_card' => '身份证号码',
        'verify_code' => '验证码',
        'terms' => '服务条款',
        'trade_channel' => '支付渠道',
        'trade_type' => '支付类型',
        'amount' => '金额',
    ],
];
