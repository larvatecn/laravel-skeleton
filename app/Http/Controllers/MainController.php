<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * 默认控制器
 *
 * @author Tongle Xu <xutongle@msn.com>
 */
class MainController extends Controller
{
    /**
     * Displays homepage.
     */
    public function index()
    {
        return view('main.index');
    }

    /**
     * Displays redirect.
     */
    public function redirect(Request $request)
    {
        return view('main.redirect', ['url' => $request->get('url')]);
    }
}
