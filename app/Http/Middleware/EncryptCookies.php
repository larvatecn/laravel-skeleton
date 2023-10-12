<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * Cookie 加密中间件
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
