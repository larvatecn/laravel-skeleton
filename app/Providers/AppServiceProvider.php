<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array 验证器
     */
    protected array $validators = [
        'domain' => \App\Validators\DomainValidator::class,//域名验证
        'hash' => \App\Validators\HashValidator::class,
        'id_card' => \App\Validators\IdCardValidator::class,//中国大陆身份证验证
        'keep_word' => \App\Validators\KeepWordValidator::class,//保留词
        'latitude' => \App\Validators\LatitudeValidator::class,//经度
        'longitude' => \App\Validators\LongitudeValidator::class,//纬度
        'mac_address' => \App\Validators\MacAddressValidator::class,//Mac 地址验证
        'mail_verify_code' => \App\Validators\MailVerifyCodeValidator::class,//邮件验证码
        'mobile' => \App\Validators\MobileValidator::class,//手机号码
        'mobile_verify' => \App\Validators\MobileVerifyValidator::class,//获取手机验证码
        'mobile_verify_code' => \App\Validators\MobileVerifyCodeValidator::class,//手机验证码
        'tel' => \App\Validators\TelPhoneValidator::class,
        'ticket' => \App\Validators\TicketValidator::class,
        'username' => \App\Validators\UsernameValidator::class,
    ];

    /**
     * @var array
     */
    protected array $morphMap = [
        'user' => \App\Models\User::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //忽略 Passport 默认迁移
        \Laravel\Passport\Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Http\Resources\Json\JsonResource::withoutWrapping();
        \Illuminate\Support\Carbon::setLocale('zh');
        $this->registerObserve();
        $this->registerValidators();
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap($this->morphMap);
    }

    /**
     * Register observes.
     */
    protected function registerObserve()
    {
    }

    /**
     * Register validators.
     */
    protected function registerValidators()
    {
        foreach ($this->validators as $rule => $validator) {
            \Illuminate\Support\Facades\Validator::extend($rule, "{$validator}@validate");
        }
    }
}
