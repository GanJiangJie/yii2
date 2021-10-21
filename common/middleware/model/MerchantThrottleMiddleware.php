<?php

namespace app\common\middleware\model;

use app\common\middleware\Middleware;
use app\common\traits\ThrottleTrait;

class MerchantThrottleMiddleware extends Middleware
{
    use ThrottleTrait;

    /**
     * @param string $param
     * @throws \yii\base\Exception
     */
    public static function handle(string $param)
    {
        @list($limit, $minute, $message) = explode(',', $param);
        if (empty(token()->token)) {
            if (!self::throttle(request()->clientIp(), $limit, 60 * $minute)) {
                tbe($message ?: '访问已超限制，请稍后再试');
            }
        }
        if (empty(token()->data)) {
            token()->check();
        }
        if (!self::throttle(tokenGet('merchant_code'), $limit, 60 * $minute)) {
            tbe($message ?: '访问已超限制，请稍后再试');
        }
    }
}