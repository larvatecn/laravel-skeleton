<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * 控制器基类
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, ValidatesRequests;
}
