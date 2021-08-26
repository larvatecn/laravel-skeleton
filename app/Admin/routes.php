<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'as' => 'admin.',
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('home');
    $router->get('settings', 'HomeController@settings')->name('settings');

    //Api
    $router->get('api/tags', 'ApiController@tags');
    $router->get('api/users', 'ApiController@users');
    $router->get('api/regions', 'ApiController@regions');
    $router->get('api/categories', 'ApiController@categories');

    //数据管理
    $router->resource('dictionary/region', 'Dictionary\RegionController');
    $router->resource('dictionary/mail-codes', 'Dictionary\MailCodeController')->only(['index']);
    $router->resource('dictionary/mobile-codes', 'Dictionary\MobileCodeController')->only(['index']);
    $router->resource('dictionary/categories', 'Dictionary\CategoryController');
    $router->resource('dictionary/tags', 'Dictionary\TagController');
    //用户
    $router->resource('user/clients', 'User\ClientController');
    $router->resource('user/members', 'User\MemberController');
    $router->resource('user/realname-auth', 'User\RealnameAuthController')->except(['edit', 'destroy']);
    $router->resource('user/socials', 'User\SocialController')->only(['index', 'show']);
    $router->resource('user/scores', 'User\ScoreController')->only(['index']);

    //模块
    $router->resource('module/friendship-links', 'Module\FriendshipLinkController');
});
