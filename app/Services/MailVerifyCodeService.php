<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Services;

use App\Mail\MailVerifyCode;
use App\Models\MailCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Larva\Support\StringHelper;

/**
 * 邮件验证码
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MailVerifyCodeService
{
    /**
     * 两次获取验证码的等待时间
     * @var int
     */
    protected $waitTime = 120;

    /**
     * 验证码有效期
     * @var int
     */
    protected $duration = 10;

    /**
     * 验证码长度
     * @var int
     */
    protected $length = 7;

    /**
     * 允许尝试验证的次数
     * @var int
     */
    protected $testLimit = 3;

    /**
     * @var string 邮件地址
     */
    protected $email;

    /**
     * 静止验证码 功能测试时生成静止验证码
     * @var string
     */
    protected $fixedVerifyCode;

    /**
     * @var string 请求的IP
     */
    protected $ip;

    /**
     * 缓存Tag
     * @var string
     */
    private $cacheTag;

    /**
     * MailVerifyCodeService constructor.
     * @param string $email
     * @param string $ip
     */
    public function __construct(string $email, string $ip)
    {
        $this->email = $email;
        $this->ip = $ip;
        $this->cacheTag = 'mc:' . $email;
    }

    /**
     * 创建实例
     * @param string $email
     * @param string $ip
     * @return MailVerifyCodeService
     */
    public static function make(string $email, string $ip): MailVerifyCodeService
    {
        return new static($email, $ip);
    }

    /**
     * 发送验证码
     * @return array
     */
    public function send(): array
    {
        //两次获取间隔小于 指定的等待时间
        if (($waitTime = time() - (int)Cache::get($this->cacheTag . 'sendTime')) < $this->waitTime) {
            $code = $this->getVerifyCode();
            return [
                'hash' => $this->generateValidationHash($code),
                'waitTime' => $this->waitTime - $waitTime,
                'email' => $this->email,
            ];
        } else {
            $verifyCode = $this->getVerifyCode(true);
            Mail::to($this->email)->send(new MailVerifyCode($verifyCode));
            MailCode::build($this->email, $this->ip, $verifyCode);
            Cache::put($this->cacheTag . 'sendTime', time(), Carbon::now()->addSeconds($this->waitTime));
            return [
                'hash' => $this->generateValidationHash($verifyCode),
                'waitTime' => $this->waitTime,
                'email' => $this->email,
            ];
        }
    }

    /**
     * 获取验证码
     * @param boolean $regenerate 是否重新生成验证码
     * @return string 验证码
     */
    public function getVerifyCode(bool $regenerate = false): string
    {
        if ($this->fixedVerifyCode !== null) {
            return $this->fixedVerifyCode;
        }
        $verifyCode = Cache::get($this->cacheTag . 'verifyCode');
        if ($verifyCode === null || $regenerate) {
            $verifyCode = StringHelper::randomInteger($this->length);
            Cache::put($this->cacheTag . 'verifyCode', $verifyCode, Carbon::now()->addMinutes($this->duration));
            Cache::put($this->cacheTag . 'verifyCount', 0, Carbon::now()->addMinutes($this->duration));
        }
        return $verifyCode;
    }

    /**
     * 验证输入，看看它是否与生成的代码相匹配
     * @param string|int $input user input
     * @param boolean $caseSensitive whether the comparison should be case-sensitive
     * @return boolean whether the input is valid
     */
    public function validate($input, bool $caseSensitive): bool
    {
        $code = $this->getVerifyCode();
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        $count = Cache::get($this->cacheTag . 'verifyCount', 0);
        $count = $count + 1;
        if ($valid || $count > $this->testLimit && $this->testLimit > 0) {
            MailCode::makeUsed($this->email, $code);
            $this->getVerifyCode(true);
        }
        //更新计数器
        if (!$valid) {
            Cache::put($this->cacheTag . 'verifyCount', $count, Carbon::now()->addMinutes($this->duration));
        } else {//验证通过清楚计时器
            Cache::forget($this->cacheTag . 'verifyCount');
        }
        return $valid;
    }

    /**
     * 生成一个可以用于客户端验证的哈希。
     * @param string $code 验证码
     * @return string 用户客户端验证的哈希码
     */
    public function generateValidationHash(string $code): string
    {
        for ($h = 0, $i = strlen($code) - 1; $i >= 0; --$i) {
            $h += intval($code[$i]);
        }
        return (string)$h;
    }

    /**
     * 获取今日IP地址的发送次数
     * @return int
     */
    public function getIpSendCount(): int
    {
        return MailCode::getIpTodayCount($this->ip);
    }

    /**
     * 获取今日Email发送次数
     * @return int
     */
    public function getMailSendCount(): int
    {
        return MailCode::getMailTodayCount($this->email);
    }

    /**
     * 获取今日总发送次数
     * @return int
     */
    public function getSendCount(): int
    {
        return MailCode::getTodayCount($this->email, $this->ip);
    }

    /**
     * 设置验证码的测试限制
     * @param int $testLimit
     * @return $this
     */
    public function setTestLimit(int $testLimit): MailVerifyCodeService
    {
        $this->testLimit = $testLimit;
        return $this;
    }

    /**
     * 设置两次获取验证码的等待时间
     * @param int $waitTime
     * @return $this
     */
    public function setWaitTime(int $waitTime): MailVerifyCodeService
    {
        $this->waitTime = $waitTime;
        return $this;
    }

    /**
     * 设置验证码有效期
     * @param int $duration 单位分钟
     * @return $this
     */
    public function setDuration(int $duration): MailVerifyCodeService
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * 设置验证码长度
     * @param int $length
     * @return $this
     */
    public function setLength(int $length): MailVerifyCodeService
    {
        $this->length = $length;
        return $this;
    }

    /**
     * 设置请求的IP地址
     * @param string $ip
     * @return $this
     */
    public function setIp(string $ip): MailVerifyCodeService
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * 设置静态验证码
     * @param string $code
     * @return MailVerifyCodeService
     */
    public function setFixedVerifyCode(string $code): MailVerifyCodeService
    {
        $this->fixedVerifyCode = $code;
        return $this;
    }
}
