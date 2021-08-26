<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Larva\Settings\Facade\Settings;

/**
 * 默认设置
 * @author Tongle Xu <xutongle@gmail.com>
 */
class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //基本设置
        Settings::set('system.title', 'Laravel - 关注实时互联网热点排行，互联网一站式运营平台！');
        Settings::set('system.keywords', '拉瓦,拉瓦科技,自媒体,科技媒体,大数据,企业级服务,数据分析,运营分析,热度排行榜,运营决策,品牌分析');
        Settings::set('system.description', '拉瓦科技洞悉互联网企业级服务市场，挖掘互联网与移动互联网行业发展规律及数据价值，站在创业者身后记录互联网科技的变革与企业级服务行业发展。');
        Settings::set('system.icp_record', '鲁ICP备19007076号-4');
        Settings::set('system.police_record', '');
        Settings::set('system.support_email', 'support@larva.com.cn');
        Settings::set('system.lawyer_email', 'lawyer@larva.com.cn');

        //系统设置
        Settings::set('sms.ip_count', '20', 'int');
        Settings::set('sms.mobile', '10', 'int');

        Settings::set('system.download_remote_pictures', '1', 'bool');
        Settings::set('system.local_censor', '1', 'bool');
        Settings::set('system.cloud_censor', '0', 'bool');
        Settings::set('system.cloud_nlp', '0', 'bool');
        Settings::set('system.sitemap_cache', '60', 'int');
        Settings::set('system.sitemap_static', '1', 'bool');
        Settings::set('system.sitemap_chunk', '5000', 'int');

        //用户设置
        Settings::set('user.enable_registration', '1', 'bool');//启用注册
        Settings::set('user.enable_socialite_auto_registration', '1', 'bool');
        Settings::set('user.enable_sms_auto_registration', '1', 'bool');
        Settings::set('user.enable_password_recovery', '1', 'bool');
        Settings::set('user.enable_welcome_email', '1', 'bool');
        Settings::set('user.enable_login_email', '0', 'bool');
        Settings::set('user.enable_register_ticket', '0', 'bool');
        Settings::set('user.enable_login_ticket', '0', 'bool');

        Settings::set('tongji.baidu_siteid', '');
        Settings::set('tongji.baidu_username', '');
        Settings::set('tongji.baidu_password', '');
        Settings::set('tongji.baidu_token', '');

        //其他设置
        Settings::set('system.baidu_site_token', '');
        Settings::set('system.bing_api_key', '');
        Settings::set('system.google_adsense_client', '');
    }
}
