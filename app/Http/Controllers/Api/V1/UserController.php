<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\MailRegisterRequest;
use App\Http\Requests\Api\V1\MobileRegisterRequest;
use App\Http\Requests\Api\V1\ModifyAvatarRequest;
use App\Http\Requests\Api\V1\ModifyMailRequest;
use App\Http\Requests\Api\V1\ModifyMobileRequest;
use App\Http\Requests\Api\V1\ModifyPasswordRequest;
use App\Http\Requests\Api\V1\ModifyProfileRequest;
use App\Http\Requests\Api\V1\ResetPasswordByMobileRequest;
use App\Http\Requests\Api\V1\VerifyMobileRequest;
use App\Http\Resources\Api\V1\UserExtraResource;
use App\Http\Resources\Api\V1\LoginHistoriesResource;
use App\Http\Resources\Api\V1\ScoreResource;
use App\Http\Resources\Api\V1\UserListResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Score;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 用户接口
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['exists', 'mobileRegister', 'emailRegister', 'resetPasswordByMobile']);
    }

    /**
     * 检查接口
     * @param Request $request
     * @return array
     */
    public function exists(Request $request): array
    {
        if ($request->has('username') && !empty($request->get('username'))) {
            return ['exist' => !User::withTrashed()->where('username', $request->get('username'))->exists()];
        } elseif ($request->has('email') && !empty($request->get('email'))) {
            return ['exist' => !User::withTrashed()->where('email', $request->get('email'))->exists()];
        } elseif ($request->has('mobile') && !empty($request->get('mobile'))) {
            return ['exist' => !User::withTrashed()->where('mobile', $request->get('mobile'))->exists()];
        } else {
            throw new BadRequestHttpException('Bad request');
        }
    }

    /**
     * 手机注册接口
     * @param MobileRegisterRequest $request
     * @return UserResource
     */
    public function mobileRegister(MobileRegisterRequest $request): UserResource
    {
        if (!settings('user.enable_registration')) {
            throw new AccessDeniedHttpException(__('user.registration_closed'));
        }
        event(new Registered($user = User::createByMobile($request->mobile, $request->password)));
        $user->connectReferrer($request->invite_code);
        return new UserResource($user);
    }

    /**
     * 邮箱注册接口
     * @param MailRegisterRequest $request
     * @return UserResource
     */
    public function emailRegister(MailRegisterRequest $request): UserResource
    {
        if (!settings('user.enable_registration')) {
            throw new AccessDeniedHttpException(__('user.registration_closed'));
        }
        event(new Registered($user = User::createByUsernameAndEmail($request->username, $request->email, $request->password)));
        $user->connectReferrer($request->invite_code);
        return new UserResource($user);
    }

    /**
     * 发送激活邮件
     * @param Request $request
     * @return JsonResponse
     */
    public function sendVerificationMail(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'message' => __('user.email_verification_notification_been_sent'),
        ]);
    }

    /**
     * 通过短信重置密码
     * @param ResetPasswordByMobileRequest $request
     * @return ResponseFactory|Response
     */
    public function resetPasswordByMobile(ResetPasswordByMobileRequest $request)
    {
        if (($user = User::query()->where('mobile', $request->mobile)->first()) != null) {
            /** @var User $user */
            $user->resetPassword($request->password);
            return response('', 200);
        } else {
            throw new NotFoundHttpException('User not found.');
        }
    }

    /**
     * 获取个人资料
     * @uri /api/v1/user/profile
     * @param Request $request
     * @return UserResource
     */
    public function profile(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * 获取用户扩展信息
     * @router /api/v1/user/extra
     * @param Request $request
     * @return UserExtraResource
     */
    public function extra(Request $request): UserExtraResource
    {
        return new UserExtraResource($request->user());
    }

    /**
     * 验证手机号码
     * @param VerifyMobileRequest $request
     * @return ResponseFactory|Response
     */
    public function verifyMobile(VerifyMobileRequest $request)
    {
        if (!$request->user()->hasVerifiedMobile()) {
            $request->user()->markMobileAsVerified();
        }
        return response('', 200);
    }

    /**
     * 修改邮箱
     * @param ModifyMailRequest $request
     * @return ResponseFactory|Response
     */
    public function modifyEMail(ModifyMailRequest $request)
    {
        $request->user()->resetEmail($request->email);
        return response('', 200);
    }

    /**
     * 修改手机号码
     * @param ModifyMobileRequest $request
     * @return ResponseFactory|Response
     */
    public function modifyMobile(ModifyMobileRequest $request)
    {
        $request->user()->resetMobile($request->mobile);
        return response('', 200);
    }

    /**
     * 修改个人资料
     * @router /api/v1/user/profile
     * @param ModifyProfileRequest $request
     * @return UserResource
     */
    public function modifyProfile(ModifyProfileRequest $request): UserResource
    {
        $request->user()->profile->update($request->validated());
        $request->user()->update($request->only(['username']));
        return new UserResource($request->user());
    }

    /**
     * 修改头像
     * @param ModifyAvatarRequest $request
     * @return ResponseFactory|Response
     */
    public function modifyAvatar(ModifyAvatarRequest $request)
    {
        FileService::make()->uploadAvatar($request->user(), $request->file('avatar'));
        return response('', 200);
    }

    /**
     * 修改密码接口
     * @param ModifyPasswordRequest $request
     * @return ResponseFactory|Response
     */
    public function modifyPassword(ModifyPasswordRequest $request)
    {
        $request->user()->resetPassword($request->password);
        return response('', 200);
    }

    /**
     * 搜索用户
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $q = $request->input('q');
        $users = User::query()
            ->where('id', '<>', $request->user()->id)
            ->where('username', 'like', "$q%")
            ->orderByDesc('id')
            ->take(10)->get();
        return UserListResource::collection($users);
    }

    /**
     * 注销并删除自己的账户
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request): Response
    {
        $request->user()->delete();
        return $this->withNoContent();
    }

    /**
     * 获取登录历史
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function loginHistories(Request $request): AnonymousResourceCollection
    {
        $loginHistories = $request->user()->loginHistories()->orderByDesc('id')->paginate();
        return LoginHistoriesResource::collection($loginHistories);
    }

    /**
     * 积分交易明细
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function scores(Request $request): AnonymousResourceCollection
    {
        $transaction = Score::with(['user'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->paginate();
        return ScoreResource::collection($transaction);
    }
}
