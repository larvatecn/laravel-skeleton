<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Services;

use App\Models\MobileCode;
use App\Sms\VerifyCodeMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Larva\Sms\Sms;
use Larva\Support\StringHelper;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

/**
 * 手机验证码
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MobileVerifyCodeService
{
    /**
     * @var string
     */
    protected $mobile;

    /**
     * 两次获取验证码的等待时间
     * @var int
     */
    protected $waitTime = 60;

    /**
     * 验证码有效期
     * @var int
     */
    protected $duration = 10;

    /**
     * 最长长度
     * @var int
     */
    protected $length = 6;

    /**
     * 静止验证码 功能测试时生成静止验证码
     * @var string
     */
    protected $fixedVerifyCode;

    /**
     * 允许尝试验证的次数
     * @var int
     */
    protected $testLimit = 3;

    /**
     * @var string 请求的IP
     */
    protected $ip;

    /**
     * @var string 验证码使用场景
     */
    protected $scene;

    /**
     * 缓存Tag
     * @var string
     */
    private $cacheTag;

    /**
     * MobileVerifyCodeService constructor.
     * @param string|int $mobile
     * @param string $ip
     * @param string $scene
     */
    public function __construct($mobile, string $ip, string $scene = 'default')
    {
        $this->mobile = $mobile;
        $this->ip = $ip;
        $this->scene = $scene;
        $this->cacheTag = 'sc:' . $mobile;
    }

    /**
     * 创建实例
     * @param string|int $mobile
     * @param string $ip
     * @param string|null $scene
     * @return MobileVerifyCodeService
     */
    public static function make($mobile, string $ip, string $scene = null): MobileVerifyCodeService
    {
        $scene = $scene ?? 'default';
        return new static($mobile, $ip, $scene);
    }

    /**
     * 发送验证码
     * @return array
     */
    public function send(): array
    {
        //两次获取间隔小于 指定的等待时间
        if (($waitTime = time() - Cache::get($this->cacheTag . 'sendTime')) < $this->waitTime) {
            $code = $this->getVerifyCode();
            return [
                'hash' => $this->generateValidationHash($code),
                'waitTime' => $this->waitTime - $waitTime,
                'mobile' => $this->mobile,
            ];
        } else {
            $verifyCode = $this->getVerifyCode(true);
            try {
                Sms::send($this->mobile, new VerifyCodeMessage([
                    'code' => $verifyCode,
                    'duration' => $this->duration,
                    'scene' => $this->scene
                ]));
            } catch (NoGatewayAvailableException $exception) {
                foreach ($exception->getExceptions() as $e) {
                    Log::error($e->getMessage());
                }
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
            MobileCode::build($this->mobile, $this->ip, $verifyCode, $this->scene);
            Cache::put($this->cacheTag . 'sendTime', time(), Carbon::now()->addSeconds($this->waitTime));
            return [
                'hash' => $this->generateValidationHash($verifyCode),
                'waitTime' => $this->waitTime,
                'mobile' => $this->mobile,
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
            MobileCode::makeUsed($this->mobile, $code);
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
     * 获取IP地址的发送次数
     * @return int
     */
    public function getIpSendCount(): int
    {
        return MobileCode::getIpTodayCount($this->ip);
    }

    /**
     * 获取手机号发送次数
     * @return int
     */
    public function getMobileSendCount(): int
    {
        return MobileCode::getMobileTodayCount($this->mobile);
    }

    /**
     * 获取总发送次数
     * @return int
     */
    public function getSendCount(): int
    {
        return $this->getMobileSendCount() + $this->getIpSendCount();
    }

    /**
     * 设置验证码的测试限制
     * @param int $testLimit
     * @return $this
     */
    public function setTestLimit(int $testLimit): MobileVerifyCodeService
    {
        $this->testLimit = $testLimit;
        return $this;
    }

    /**
     * 设置验证场景
     * @param string $scene
     * @return $this
     */
    public function setScene(string $scene): MobileVerifyCodeService
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * 设置两次获取验证码的等待时间
     * @param int $waitTime
     * @return $this
     */
    public function setWaitTime(int $waitTime): MobileVerifyCodeService
    {
        $this->waitTime = $waitTime;
        return $this;
    }

    /**
     * 设置验证码有效期
     * @param int $duration 单位分钟
     * @return $this
     */
    public function setDuration(int $duration): MobileVerifyCodeService
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * 设置验证码长度
     * @param int $length
     * @return $this
     */
    public function setLength(int $length): MobileVerifyCodeService
    {
        $this->length = $length;
        return $this;
    }

    /**
     * 设置请求的IP地址
     * @param string $ip
     * @return $this
     */
    public function setIp(string $ip): MobileVerifyCodeService
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * 设置静态验证码
     * @param string $code
     * @return $this
     */
    public function setFixedVerifyCode(string $code): MobileVerifyCodeService
    {
        $this->fixedVerifyCode = $code;
        return $this;
    }
}
