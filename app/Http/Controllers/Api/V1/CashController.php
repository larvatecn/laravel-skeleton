<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RechargeRequest;
use App\Http\Requests\Api\V1\WithdrawalsRequest;
use App\Http\Resources\Api\V1\ChargeResource;
use App\Http\Resources\Api\V1\TransactionResource;
use App\Http\Resources\Api\V1\WithdrawalsResource;
use App\Models\Recharge;
use App\Models\Transaction;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * 现金账户
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CashController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * 交易明细
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function transaction(Request $request): AnonymousResourceCollection
    {
        $transaction = Transaction::with(['user', 'source'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->paginate();
        return TransactionResource::collection($transaction);
    }

    /**
     * 余额充值
     * @param RechargeRequest $request
     * @return ChargeResource
     */
    public function recharge(RechargeRequest $request): ChargeResource
    {
        $recharge = Recharge::create($request->validated());
        return new ChargeResource($recharge->charge);
    }

    /**
     * 余额提现
     * @param WithdrawalsRequest $request
     * @return WithdrawalsResource
     */
    public function withdrawals(WithdrawalsRequest $request): WithdrawalsResource
    {
        $withdrawals = Withdrawals::create($request->validated());
        return new WithdrawalsResource($withdrawals);
    }
}
