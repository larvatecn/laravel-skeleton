<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * 前端 Ajax
 * @author Tongle Xu <xutongle@gmail.com>
 */
class AjaxController extends Controller
{
    /**
     * 前端 Ajax 获取用户信息
     * @param Request $request
     * @return JsonResponse
     */
    public function info(Request $request): JsonResponse
    {
        $result = [
            'isLogin' => false,
            'isVip' => false,
            'qq_client_id' => config('services.qq.client_id'),
            'weibo_client_id' => config('services.weibo.client_id'),
            'google_adsense_client' => settings('system.google_adsense_client'),
            'captchaId' => settings('system.captcha_aid')
        ];
        if (($user = $request->user()) != null) {
            $result['isLogin'] = true;
            $result['isVip'] = false;
            $result['id'] = $user->id;
            $result['username'] = $user->username;
            $result['avatar'] = $user->avatar;
            $result['email'] = $user->email;
            $result['mobile'] = $user->mobile;
            $result['amount'] = $user->amount;
            $result['score'] = $user->score;
            $result['unreadNotificationCount'] = $user->unreadNotifications()->count();
        }
        return response()->json($result);
    }

    /**
     * Tag Ajax加载
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function tags(Request $request): LengthAwarePaginator
    {
        $query = Tag::query()->select(['id', 'name', 'frequency'])->orderByDesc('frequency');
        $q = $request->get('q');
        if (!empty($q) && mb_strlen($q) >= 1) {
            $query->where('name', 'LIKE', '%' . $q . '%');
        }
        return $query->paginate(10);
    }

    /**
     * 加载地区
     * @param Request $request
     * @return Collection
     */
    public function regions(Request $request): Collection
    {
        $parent_id = intval($request->get('parent_id', 0));
        return Region::getRegion($parent_id, ['id', DB::raw('name as text')]);
    }
}
