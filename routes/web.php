<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\MainController::class, 'index']);
Route::get('redirect', [\App\Http\Controllers\MainController::class, 'redirect']);
Route::get('manifest.json', [\App\Http\Controllers\MainController::class, 'manifest']);

Auth::routes(['verify' => true]);
Route::get('register/mobile', [\App\Http\Controllers\Auth\RegisterController::class, 'showMobileRegistrationForm'])->name('mobile.register');
Route::post('register/mobile', [\App\Http\Controllers\Auth\RegisterController::class, 'mobileRegister'])->name('mobile.register.store');

//社交账户登录
Route::get('auth/social/{provider}', [\App\Http\Controllers\Auth\SocialController::class, 'redirectToProvider']);
Route::get('auth/social/{provider}/callback', [\App\Http\Controllers\Auth\SocialController::class, 'handleProviderCallback']);
Route::get('auth/social/{provider}/connect', [\App\Http\Controllers\Auth\SocialController::class, 'handleProviderConnect']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
/**
 * Ajax 调用
 */
Route::group(['prefix' => 'ajax'], function () {
    Route::get('info', [App\Http\Controllers\AjaxController::class, 'info']);//获取用户登录状态
    Route::get('tags', [App\Http\Controllers\AjaxController::class, 'tags'])->name('ajax.tags');//Tags加载
    Route::get('regions', [App\Http\Controllers\AjaxController::class, 'regions'])->name('ajax.regions');//地区联动加载
});
