<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Sms;

use Larva\Sms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;

/**
 * 短信验证码
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class VerifyCodeMessage extends Message
{
    /**
     * 可用网关
     *
     * @var array
     */
    protected $gateways = ['qcloud', 'aliyun'];

    /**
     * 验证码
     *
     * @var int
     */
    protected $code;

    /**
     * 验证码有效期
     *
     * @var int
     */
    protected $duration;

    /**
     * 验证码场景
     *
     * @var string
     */
    protected $scene;

    /**
     * 短信模板ID
     *
     * @var array
     */
    protected $templateCodes = [
        'qcloud' => [
            'default' => '1039506',//默认验证码
            'register' => '1039453',//注册验证码
            'login' => '1039451',//登录验证码
            'resetPassword' => '1039454',//修改密码
            'authentication' => '1039457',//身份验证验证码
            'modifyProfile' => '1039456',//修改资料验证码
        ],
        'aliyun' => [
            'default' => 'SMS_176526437',//默认验证码
            'register' => 'SMS_156605005',//注册验证码
            'login' => 'SMS_156605007',//登录验证码
            'resetPassword' => 'SMS_156605004',//修改密码
            'authentication' => 'SMS_156605008',//身份验证验证码
            'modifyProfile' => 'SMS_156605003',//修改资料验证码
        ]
    ];

    /**
     * 定义使用模板发送方式平台所需要的模板 ID
     * @param GatewayInterface|null $gateway
     * @return string
     */
    public function getTemplate(GatewayInterface $gateway = null): string
    {
        $templates = $this->templateCodes[$gateway->getName()] ?? [];
        return $templates[$this->scene] ?? $templates['default'];
    }

    /**
     * 模板参数
     * @param GatewayInterface|null $gateway
     * @return array
     */
    public function getData(GatewayInterface $gateway = null): ?array
    {
        if (!is_null($gateway) && $gateway->getName() == 'qcloud') {
            return [$this->code];
        } elseif (!is_null($gateway) && $gateway->getName() == 'aliyun') {
            return ['code' => $this->code];
        }
        return null;
    }

    /**
     * 定义直接使用内容发送平台的内容
     * @param GatewayInterface|null $gateway
     * @return string
     */
    public function getContent(GatewayInterface $gateway = null): ?string
    {
        if (!is_null($gateway) && $gateway->getName() == 'qcloud') {
            return sprintf('您的验证码为：%s，该验证码5分钟内有效，请勿泄漏于他人！', $this->code);
        } elseif (!is_null($gateway) && $gateway->getName() == 'yunpian') {
            return sprintf('验证码：%s，如非本人操作，请忽略此短信。', $this->code);
        } elseif (!is_null($gateway) && $gateway->getName() == 'aliyun') {
            return sprintf('验证码：%s，如非本人操作，请忽略此短信。', $this->code);
        }
        return null;
    }
}
