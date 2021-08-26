<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Validators;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * 票据验证
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TicketValidator
{
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        $query = [
            'aid' => settings('system.captcha_aid'),
            'AppSecretKey' => settings('system.captcha_secret'),
            'Ticket' => $value,
            'Randstr' => request('randstr'),
            'UserIP' => request()->ip(),
        ];
        return $this->verify($query);
    }

    /**
     * @param array $query
     * @return bool
     */
    private function verify(array $query): bool
    {
        try {
            $response = Http::retry(3, 100)->get('https://ssl.captcha.qq.com/ticket/verify', $query);
            if ($response->successful() && !$passed = ($response['response'] ?? 0) == 1) {
                Log::error('Captcha verified fail:', compact('query', 'result'));
            }
            return $passed;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
