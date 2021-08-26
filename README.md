# Laravel 8 基础模板

[![Laravel](https://github.com/larvatecn/laravel-skeleton/actions/workflows/laravel.yml/badge.svg)](https://github.com/larvatecn/laravel-skeleton/actions/workflows/laravel.yml)
[![License](https://poser.pugx.org/larva/laravel-skeleton/license.svg)](https://packagist.org/packages/larva/laravel-skeleton)
[![Latest Stable Version](https://poser.pugx.org/larva/laravel-skeleton/v/stable.png)](https://packagist.org/packages/larva/laravel-skeleton)
[![Total Downloads](https://poser.pugx.org/larva/laravel-skeleton/downloads.png)](https://packagist.org/packages/larva/laravel-skeleton)

开箱即用的 Laravel 8 基础结构，只需很少的代码即可快速构建出一个功能完善的高颜值系统。内置丰富的常用组件，开箱即用，让开发者告别冗杂的代码，对 RESTFul 开发者非常友好。

## 环境
- PHP >= 7.4
- Fileinfo PHP Extension

## 功能特性
- [x] 内置 [dcat-admin](https://gitee.com/jqhph/dcat-admin) 管理后台；
- [x] 内置 laravel/passport 的授权机制；
- [x] 内置 laravel/passport 短信验证码登录；
- [x] 内置 laravel/passport 社交账户登录（App）；
- [x] 内置用户系统和基础接口；
- [x] 内置社交账户登录；
- [x] 内置支持图形/邮件/短信验证码；
- [x] 内置支持栏目管理、Tag管理、文章、下载；
- [x] 内置 友链、广告、轮播、死链、搜索引擎推送模块；
- [x] 内置 微信、支付宝、财务、钱包、积分模块；
- [x] 内置 Sitemap 支持；

## 可选支持（默认未安装）
- [小程序登录](https://github.com/larvatech/laravel-passport-miniprogram)
- [签名的开放API接口](https://github.com/larvatech/laravel-auth-signature-guard)
- [微信通知](https://github.com/larvatech/laravel-wechat-notification-channel)
- [友盟通知](https://github.com/larvatech/laravel-umeng-notification-channel)

## 安装

1. 创建项目

```bash
$ composer create-project larva/laravel-skeleton -vv
```

2. 创建配置文件

```bash
$ cp .env.develop .env
```

3. 创建数据表和测试数据

```bash
$ php artisan migrate --seed
```

4. 配置任务调度

```bash
* * * * * cd /app/path/project && php artisan schedule:run >> /dev/null 2>&1
```

然后访问 `http://laravel-skeleton.test/` 将会看到网站信息。

## License

[MIT license](https://opensource.org/licenses/MIT).
