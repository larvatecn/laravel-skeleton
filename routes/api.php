<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * RESTFul API version 1.
 *
 * Define the version of the interface that conforms to most of the
 * REST ful specification.
 */
Route::group(['prefix' => 'v1'], function (Illuminate\Contracts\Routing\Registrar $api) {

    /**
     * 公共接口
     */
    Route::group(['prefix' => 'common'], function (Illuminate\Contracts\Routing\Registrar $api) {
        $api->post('mobile-verify-code', [App\Http\Controllers\Api\V1\CommonController::class, 'mobileVerifyCode'])->name('mobile-verify-code');//短信验证码
        $api->post('mail-verify-code', [App\Http\Controllers\Api\V1\CommonController::class, 'mailVerifyCode'])->name('mail-verify-code');//邮件验证码
        $api->get('country', [App\Http\Controllers\Api\V1\CommonController::class, 'country'])->name('country');//国家列表
    });


    /**
     * 用户接口
     */
    Route::group(['prefix' => 'user'], function () {
        Route::post('exists', [App\Http\Controllers\Api\V1\UserController::class, 'exists']);//账号邮箱手机号检查
        Route::post('mobile-register', [App\Http\Controllers\Api\V1\UserController::class, 'mobileRegister']);//手机号注册
        Route::post('email-register', [App\Http\Controllers\Api\V1\UserController::class, 'emailRegister']);//邮箱注册
        Route::post('send-verification-mail', [App\Http\Controllers\Api\V1\UserController::class, 'sendVerificationMail']);//发送激活邮件
        Route::post('mobile-reset-password', [App\Http\Controllers\Api\V1\UserController::class, 'resetPasswordByMobile']);//通过手机重置用户登录密码
        Route::get('profile', [App\Http\Controllers\Api\V1\UserController::class, 'profile']);//获取用户个人资料
        Route::get('extra', [App\Http\Controllers\Api\V1\UserController::class, 'extra']);//获取扩展资料
        Route::post('verify-mobile', [App\Http\Controllers\Api\V1\UserController::class, 'verifyMobile']);//验证手机号码
        Route::post('email', [App\Http\Controllers\Api\V1\UserController::class, 'modifyEMail']);//修改邮箱
        Route::post('mobile', [App\Http\Controllers\Api\V1\UserController::class, 'modifyMobile']);//修改手机号码
        Route::post('profile', [App\Http\Controllers\Api\V1\UserController::class, 'modifyProfile']);//修改用户个人资料
        Route::post('avatar', [App\Http\Controllers\Api\V1\UserController::class, 'modifyAvatar']);//修改头像
        Route::post('password', [App\Http\Controllers\Api\V1\UserController::class, 'modifyPassword']);//修改密码
        Route::get('search', [App\Http\Controllers\Api\V1\UserController::class, 'search']);//搜索用户
        Route::get('login-histories', [App\Http\Controllers\Api\V1\UserController::class, 'loginHistories']);//获取登录历史
        Route::get('scores', [App\Http\Controllers\Api\V1\UserController::class, 'scores']);//积分交易明细
        Route::delete('', [App\Http\Controllers\Api\V1\UserController::class, 'destroy']);//注销并删除自己的账户
    });

    /**
     * 实名认证
     */
    Route::group(['prefix' => 'realname'], function () {
        Route::get('auth', [App\Http\Controllers\Api\V1\RealnameAuthController::class, 'status']);//查询实名认证状态
        Route::post('auth', [App\Http\Controllers\Api\V1\RealnameAuthController::class, 'putIdentity']);//提交实名认证信息
    });

    /**
     * 通知
     */
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('', [App\Http\Controllers\Api\V1\NotificationController::class, 'index']);// 通知列表
        Route::get('unread', [App\Http\Controllers\Api\V1\NotificationController::class, 'unread']);// 未读通知列表
        Route::get('unread-count', [App\Http\Controllers\Api\V1\NotificationController::class, 'unreadCount']);// 通知统计
        Route::post('mark-all-read', [App\Http\Controllers\Api\V1\NotificationController::class, 'markAllRead']);//标记所有未读通知为已读
        Route::post('mark-read', [App\Http\Controllers\Api\V1\NotificationController::class, 'markAsRead']);//标记指定未读通知为已读
    });

    /**
     * 社交账户
     */
    Route::group(['prefix' => 'social'], function () {
        Route::get('accounts', [App\Http\Controllers\Api\V1\SocialController::class, 'socialAccounts']);//获取绑定的社交账户
        Route::delete('accounts/{provider}', [App\Http\Controllers\Api\V1\SocialController::class, 'disconnect']);//解绑
        Route::get('connect/{provider}', [App\Http\Controllers\Api\V1\SocialController::class, 'connect']);//绑定社交账户
    });

    /**
     * 签到
     */
    Route::get('sign-in', [App\Http\Controllers\Api\V1\SignInController::class, 'info']);//获取签到信息
    Route::post('sign-in', [App\Http\Controllers\Api\V1\SignInController::class, 'sign']);//签到


    /**
     * 设备接口
     */
    Route::group(['prefix' => 'device'], function () {
        Route::post('register', [App\Http\Controllers\Api\V1\DeviceController::class, 'register']);//设备注册
    });

    /**
     * 友情链接
     */
    Route::get('friendship-links', App\Http\Controllers\Api\V1\FriendshipLinkController::class)->name('links');//友情链接
    /**
     * 栏目 接口
     */
    Route::group(['prefix' => 'categories'], function () {
        Route::get('', [App\Http\Controllers\Api\V1\CategoryController::class, 'index']);
        Route::get('{category}', [App\Http\Controllers\Api\V1\CategoryController::class, 'show']);
    });

    /**
     * Tag 接口
     */
    Route::group(['prefix' => 'tags'], function () {
        Route::get('', [App\Http\Controllers\Api\V1\TagController::class, 'index']);
        Route::get('{tag}', [App\Http\Controllers\Api\V1\TagController::class, 'show']);
    });

});

/**
 * RESTFul API version 2.
 *
 * Define the version of the interface that conforms to most of the
 * REST ful specification.
 */
Route::group(['prefix' => 'v2', 'as' => 'api.v2.'], function (Illuminate\Contracts\Routing\Registrar $api) {
});
