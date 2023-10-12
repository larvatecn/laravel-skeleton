<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

/**
 * 网站维护期间阻止请求中间件
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
