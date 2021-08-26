<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;
use Illuminate\Support\Arr;
use Larva\Settings\Facade\Settings;
use Larva\Settings\SettingEloquent;

/**
 * 系统设置
 * @author Tongle Xu <xutongle@gmail.com>
 */
class SettingsForm extends Form
{
    /**
     * Build a form here.
     */
    public function form()
    {
        $this->tab('基本设置', function () {
            $this->text('system.title', '网站标题')->required()->rules('required|string|min:2')->placeholder('请输入网站标题（一般不超过80个字符）');
            $this->text('system.keywords', '网站关键词')->required()->rules('required|string|min:5')->placeholder('请输入网站标题（一般不超过100个字符）');
            $this->textarea('system.description', '网站描述')->required()->rules('required|string|min:5')->placeholder('请输入网站标题（一般不超过200个字符）');
            $this->text('system.icp_record', 'ICP备案')->rules('nullable|string')->placeholder('ICP备XXXX号');
            $this->text('system.police_record', '公安备案')->rules('nullable|string')->placeholder('公安备XXXX号');
            $this->email('system.support_email', '服务邮箱')->rules('nullable|email')->placeholder('support@xxx.com');
            $this->email('system.lawyer_email', '法律邮箱')->rules('nullable|email')->placeholder('lawyer@xxx.com');
        });
        $this->tab('系统设置', function () {
            $this->switch('system.download_remote_pictures', '下载远程图片');
            $this->switch('system.local_censor', '启用本地文本智能审核');
            $this->radio('system.cloud_censor', '启用云内容安全')->options(['0' => '不开启', 'baidu' => '百度云', 'tencent' => '腾讯云']);
            $this->radio('system.cloud_nlp', '启用内容NLP')->options(['0' => '不开启', 'baidu' => '百度云', 'tencent' => '腾讯云']);
            $this->text('system.sitemap_chunk', '单个Sitemap Url 数量')->append('<span class="input-group-text">个</span>')->help('修改该值请手动删除所有已经生成的 XML 文件，防止错乱！');
            $this->text('system.sitemap_cache', 'Sitemap 缓存时间')->append('<span class="input-group-text">分钟</span>');
            $this->switch('system.sitemap_static', 'Sitemap 智能静态化')->help('在第一次请求时自动生成静态 XML 文件减轻服务器压力并自动跳过最新一页。');
        });
        $this->tab('用户设置', function () {
            $this->switch('user.enable_registration', '启用新用户注册');
            $this->switch('user.enable_socialite_auto_registration', '启用社交用户自动注册');
            $this->switch('user.enable_sms_auto_registration', '启用短信验证码注册');
            $this->switch('user.enable_miniprogram_auto_registration', '启用小程序注册');
            $this->switch('user.enable_password_recovery', '启用找回密码');
            $this->switch('user.enable_welcome_email', '发送注册欢迎邮件');
            $this->switch('user.enable_login_email', '发送登录通知邮件');
            $this->switch('user.enable_register_ticket', '启用注册滑动解锁');
            $this->switch('user.enable_login_ticket', '启用登录滑动解锁');
            $this->text('system.captcha_aid', '滑动解锁Aid')->rules('nullable|string');
            $this->text('system.captcha_secret', '滑动解锁Secret')->rules('nullable|string');
        });
        $this->tab('钱包/积分设置', function () {
            $this->text('score.signing', '签到赠送积分')->rules('nullable|numeric');
            $this->text('score.signing7day', '连续签到7天积分')->rules('nullable|numeric');
        });

        $this->tab('小程序设置', function () {
            $this->text('miniprogram.qq_appid', 'QQ小程序 Appid')->rules('nullable|string');
            $this->text('miniprogram.qq_secret', 'QQ小程序 Secret')->rules('nullable|string');
            $this->text('miniprogram.bytedance_appid', '头条小程序 Appid')->rules('nullable|string');
            $this->text('miniprogram.bytedance_secret', '头条小程序 Secret')->rules('nullable|string');
            $this->text('miniprogram.alipay_appid', '支付宝小程序 Appid')->rules('nullable|string');
            $this->text('miniprogram.alipay_secret', '支付宝小程序 Secret')->rules('nullable|string');
        });

        $this->tab('统计设置', function () {
            $this->text('tongji.baidu_siteid', '百度统计网站ID')->rules('nullable|string');
            $this->text('tongji.baidu_username', '百度统计账户')->rules('nullable|string');
            $this->password('tongji.baidu_password', '百度统计密码')->rules('nullable|string');
            $this->text('tongji.baidu_token', '百度统计Token')->rules('nullable|string');
        });

        $this->tab('其他设置', function () {
            $this->text('system.baidu_site_token', '百度推送Token')->rules('nullable|string');
            $this->text('system.bing_api_key', 'Bing推送Token')->rules('nullable|string');
            $this->text('system.google_adsense_client', 'Google Adsense 发布商ID')->rules('nullable|string');
        });
    }

    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return \Dcat\Admin\Http\JsonResponse
     */
    public function handle(array $input)
    {
        $input = Arr::dot($input);
        foreach ($input as $key => $val) {
            Settings::set($key, $val);
        }
        return $this->response()->success('操作成功！')->refresh();
    }

    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        $settings = [];
        SettingEloquent::all()->each(function ($setting) use (&$settings) {
            $settings[$setting['key']] = $setting['value'];
        });
        return $settings;
    }
}
